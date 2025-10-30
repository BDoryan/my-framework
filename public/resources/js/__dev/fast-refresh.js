console.log('[Fast Refresh] Module loaded');

const DEFAULT_RELOAD_ENDPOINT = '/__dev/fast-refresh.php';
const DEFAULT_POLL_INTERVAL = 1000;
const STORAGE_LAST_MOD = 'fast_refresh_last_mod_time';
const STORAGE_PREF_ENABLED = 'fast_refresh_enabled';

let intervalId = null;

const getClientLastModTime = () => Number(localStorage.getItem(STORAGE_LAST_MOD) ?? '0');

const setClientLastModTime = (value) => {
    if (value != null) {
        localStorage.setItem(STORAGE_LAST_MOD, String(value));
    }
};

const pollServer = async () => {
    const client_last_mod_time = getClientLastModTime();

    try {
        const response = await fetch(
            `${DEFAULT_RELOAD_ENDPOINT}?${new URLSearchParams({client_last_mod_time}).toString()}`,
            {
                cache: 'no-store',
                headers: {
                    Accept: 'application/json'
                },
            }
        );

        if (!response.ok) {
            throw new Error(`Fast refresh request failed with status ${response.status}`);
        }

        const payload = await response.json();
        const {reload, last_mod_time} = payload;

        if (reload === true) {
            if (last_mod_time != null) {
                setClientLastModTime(last_mod_time);
                console.log('[Fast Refresh] Reloading page (server changes detected)');
                window.location.reload();
            }
        } else if (last_mod_time != null) {
            setClientLastModTime(last_mod_time);
        }
    } catch (error) {
        console.warn('[Fast Refresh] Polling failed:', error);
    }
};

export const startFastRefresh = () => {
    if (intervalId != null) {
        return;
    }

    localStorage.setItem(STORAGE_PREF_ENABLED, 'on');
    intervalId = setInterval(pollServer, DEFAULT_POLL_INTERVAL);
    // Trigger first poll without waiting the interval
    pollServer().catch((error) => console.warn('[Fast Refresh] Initial poll failed:', error));
    console.log('[Fast Refresh] Auto reload enabled');
};

export const stopFastRefresh = () => {
    if (intervalId == null) {
        return;
    }

    clearInterval(intervalId);
    intervalId = null;
    localStorage.setItem(STORAGE_PREF_ENABLED, 'off');
    console.log('[Fast Refresh] Auto reload disabled');
};

export const isFastRefreshEnabled = () => localStorage.getItem(STORAGE_PREF_ENABLED) !== 'off';

// Auto start if preference not explicitly disabled
if (isFastRefreshEnabled()) {
    startFastRefresh();
}

// Expose helpers for other dev tools
window.FastRefresh = {
    start: startFastRefresh,
    stop: stopFastRefresh,
    isEnabled: isFastRefreshEnabled,
};
