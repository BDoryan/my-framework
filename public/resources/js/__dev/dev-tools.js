console.log('[Dev Tools] Loading development tools UI...');

import {startFastRefresh, stopFastRefresh, isFastRefreshEnabled} from './fast-refresh.js';

const TOGGLE_ID = 'dev-tools-fast-refresh-toggle';
const STYLE_ATTR = 'data-fast-refresh-toggle-style';

const ensureStyles = () => {
    if (document.querySelector(`style[${STYLE_ATTR}]`)) {
        return;
    }

    const style = document.createElement('style');
    style.setAttribute(STYLE_ATTR, 'true');
    style.textContent = `
        #${TOGGLE_ID} {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 4000;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        #${TOGGLE_ID} button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1rem;
            border-radius: 999px;
            border: 0;
            cursor: pointer;
            background: rgba(15, 14, 40, 0.92);
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.35);
            transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }
        #${TOGGLE_ID} button[data-state='on'] {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.95), rgba(111, 66, 193, 0.95));
            color: #fff;
            box-shadow: 0 1rem 2rem rgba(13, 110, 253, 0.35);
        }
        #${TOGGLE_ID} button:active {
            transform: scale(0.97);
        }
        #${TOGGLE_ID} button:focus-visible {
            outline: 2px solid rgba(255, 255, 255, 0.7);
            outline-offset: 2px;
        }
        #${TOGGLE_ID} button .dot {
            width: 0.55rem;
            height: 0.55rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.45);
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }
        #${TOGGLE_ID} button[data-state='on'] .dot {
            background: #fff;
            box-shadow: 0 0 0.45rem rgba(255, 255, 255, 0.65);
        }
        #${TOGGLE_ID} button .text {
            display: inline-flex;
            flex-direction: column;
            line-height: 1.1;
        }
        #${TOGGLE_ID} button .text span {
            display: block;
        }
        #${TOGGLE_ID} button .text .status {
            font-size: 0.65rem;
            letter-spacing: 0.12em;
            opacity: 0.85;
        }
        @media (max-width: 640px) {
            #${TOGGLE_ID} {
                bottom: 0.75rem;
                right: 0.75rem;
            }
        }
    `;

    document.head.appendChild(style);
};

const renderToggle = () => {
    if (document.getElementById(TOGGLE_ID)) {
        return;
    }

    ensureStyles();

    const container = document.createElement('div');
    container.id = TOGGLE_ID;

    const button = document.createElement('button');
    button.type = 'button';
    button.setAttribute('aria-label', 'Toggle fast refresh');
    button.setAttribute('aria-pressed', 'false');
    button.dataset.state = 'off';

    const dot = document.createElement('span');
    dot.className = 'dot';
    dot.setAttribute('aria-hidden', 'true');

    const textWrapper = document.createElement('span');
    textWrapper.className = 'text';

    const title = document.createElement('span');
    title.textContent = 'Fast refresh';

    const status = document.createElement('span');
    status.className = 'status';
    status.textContent = 'Off';

    textWrapper.append(title, status);
    button.append(dot, textWrapper);
    container.appendChild(button);
    document.body.appendChild(container);

    const updateButtonState = () => {
        const enabled = isFastRefreshEnabled();
        button.dataset.state = enabled ? 'on' : 'off';
        button.setAttribute('aria-pressed', enabled ? 'true' : 'false');
        button.setAttribute('aria-label', enabled ? 'Disable fast refresh' : 'Enable fast refresh');
        status.textContent = enabled ? 'On' : 'Off';
    };

    button.addEventListener('click', () => {
        if (isFastRefreshEnabled()) {
            stopFastRefresh();
        } else {
            startFastRefresh();
        }
        updateButtonState();
    });

    updateButtonState();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', renderToggle);
} else {
    renderToggle();
}
