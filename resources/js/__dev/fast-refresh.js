const DEFAULT_RELOAD_ENDPOINT = '/__dev/fast-refresh.php';
const DEFAULT_POLL_INTERVAL = 2000;

// Get from storage the last modification time of the server files
const client_last_mod_time = localStorage.getItem('fast_refresh_last_mod_time') || '';

setInterval(async () => {
    try {
        const response = await fetch(DEFAULT_RELOAD_ENDPOINT + '?' + new URLSearchParams({
            client_last_mod_time
        }).toString(), {
            cache: 'no-store',
            headers: {
                Accept: 'application/json'
            },
        });

        if (!response.ok)
            throw new Error(`Fast refresh request failed with status ${response.status}`);

        const payload = await response.json();
        const {reload, last_mod_time} = payload;

        if (reload === true) {
            if (last_mod_time != null) {
                window.location.reload();
                console.log('Fast refresh: reloading page due to server changes.');
            }
            localStorage.setItem('fast_refresh_last_mod_time', last_mod_time);
        }
    } catch (error) {
        console.warn('Fast refresh check failed:', error);
    }
}, DEFAULT_POLL_INTERVAL);