<?php
declare(strict_types=1);

$status_code = $status_code ?? 500;
$status_label = $status_label ?? 'Erreur';
$message = $message ?? "Une erreur est survenue.";
$request_path = $request_path ?? '/';
$is_dev = $is_dev ?? false;
$exceptions = $exceptions ?? [];
$timestamp = date('Y-m-d H:i:s');
?>

<style>
    .mf-error {
        margin: 0 auto;
        max-width: 960px;
        padding: 3rem 1.5rem 4rem;
        font-family: "Inter", "Segoe UI", sans-serif;
        color: #1e1f3a;
    }

    .mf-error__panel {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid rgba(34, 37, 60, 0.08);
        box-shadow: 0 20px 60px rgba(18, 21, 46, 0.08);
        padding: 3rem;
        position: relative;
        overflow: hidden;
    }

    .mf-error__panel::after {
        content: "";
        position: absolute;
        inset: auto -10% 10% auto;
        width: 280px;
        height: 280px;
        background: radial-gradient(circle at center, rgba(249, 50, 44, 0.18) 0%, rgba(249, 50, 44, 0) 70%);
        pointer-events: none;
    }

    .mf-error__badge {
        display: inline-block;
        padding: 0.35rem 0.9rem;
        border-radius: 999px;
        background: rgba(249, 50, 44, 0.1);
        color: #f9322c;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .mf-error__title {
        font-size: clamp(2rem, 4vw, 2.6rem);
        margin: 0 0 1rem;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .mf-error__status {
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        padding: 0.6rem 1.6rem;
        border-radius: 999px;
        background: #f7f3ff;
        color: #5a3f94;
        font-weight: 600;
        margin-bottom: 1.75rem;
    }

    .mf-error__code {
        font-size: 1.25rem;
        font-weight: 700;
    }

    .mf-error__message {
        font-size: 1.05rem;
        color: #2e314f;
        margin-bottom: 1.5rem;
        line-height: 1.7;
    }

    .mf-error__request,
    .mf-error__timestamp {
        font-size: 0.95rem;
        color: #565978;
        margin: 0.4rem 0;
    }

    .mf-error__hint {
        margin-top: 2rem;
        font-size: 0.95rem;
        color: #707497;
    }

    .mf-error__actions {
        margin-top: 2.5rem;
        display: flex;
        gap: 1rem;
    }

    .mf-error__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.8rem;
        border-radius: 999px;
        font-weight: 600;
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        border: none;
        cursor: pointer;
    }

    .mf-error__button-primary {
        background: linear-gradient(130deg, #ff5e57, #f9322c);
        color: #ffffff;
        box-shadow: 0 12px 30px rgba(249, 50, 44, 0.22);
    }

    .mf-error__button-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(249, 50, 44, 0.28);
    }

    .mf-error__debug {
        margin-top: 3rem;
        padding: 2.5rem;
        border-radius: 1rem;
        background: #0f172a;
        color: #e2e8f0;
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .mf-error__debug h2 {
        margin-top: 0;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .mf-error__exception + .mf-error__exception {
        margin-top: 2.5rem;
        padding-top: 2.5rem;
        border-top: 1px solid rgba(148, 163, 184, 0.25);
    }

    .mf-error__exception-message {
        margin: 0 0 1.25rem;
        font-size: 1rem;
        color: #f1f5f9;
    }

    .mf-error__trace {
        list-style: decimal;
        margin: 0;
        padding-left: 1.5rem;
        display: grid;
        gap: 0.6rem;
    }

    .mf-error__trace-call {
        font-weight: 600;
        color: #cbd5f5;
        display: block;
    }

    .mf-error__trace-location {
        font-size: 0.85rem;
        color: #94a3b8;
    }

    @media (max-width: 640px) {
        .mf-error__panel {
            padding: 2.25rem 1.75rem;
        }

        .mf-error__debug {
            padding: 1.75rem;
        }

        .mf-error__actions {
            flex-direction: column;
        }
    }
</style>

<div class="mf-error">
    <div class="mf-error__panel">
        <span class="mf-error__badge">MyFramework</span>
        <h1 class="mf-error__title">Oups, quelque chose s'est mal passé.</h1>
        <div class="mf-error__status">
            <span class="mf-error__code"><?= htmlspecialchars((string) $status_code, ENT_QUOTES, 'UTF-8') ?></span>
            <span class="mf-error__label"><?= htmlspecialchars($status_label, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <p class="mf-error__message"><?= nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) ?></p>
        <p class="mf-error__request">Requête&nbsp;: <code><?= htmlspecialchars($request_path, ENT_QUOTES, 'UTF-8') ?></code></p>
        <p class="mf-error__timestamp">Horodatage&nbsp;: <?= htmlspecialchars($timestamp, ENT_QUOTES, 'UTF-8') ?></p>

        <?php if (!$is_dev): ?>
            <p class="mf-error__hint">Notre équipe a été automatiquement informée. Vous pouvez actualiser la page ou revenir plus tard.</p>
        <?php else: ?>
            <p class="mf-error__hint">Le mode développement est actif : les détails complets de l'exception sont affichés ci-dessous.</p>
        <?php endif; ?>

        <div class="mf-error__actions">
            <a class="mf-error__button mf-error__button-primary" href="/">Retour à l'accueil</a>
        </div>
    </div>

    <?php if ($is_dev && !empty($exceptions)): ?>
        <div class="mf-error__debug">
            <?php foreach ($exceptions as $index => $exception): ?>
                <div class="mf-error__exception">
                    <h2>Exception <?= htmlspecialchars((string) ($index + 1), ENT_QUOTES, 'UTF-8') ?> &mdash; <?= htmlspecialchars($exception['class'] ?? 'Exception', ENT_QUOTES, 'UTF-8') ?></h2>
                    <p class="mf-error__exception-message">
                        <?= nl2br(htmlspecialchars($exception['message'] ?? '', ENT_QUOTES, 'UTF-8')) ?><br>
                        <small>Dans <?= htmlspecialchars($exception['file'] ?? 'n/a', ENT_QUOTES, 'UTF-8') ?>:<?= isset($exception['line']) ? (int) $exception['line'] : 'n/a' ?></small>
                    </p>

                    <?php if (!empty($exception['trace'])): ?>
                        <ol class="mf-error__trace">
                            <?php foreach ($exception['trace'] as $frame): ?>
                                <li>
                                    <span class="mf-error__trace-call"><?= htmlspecialchars($frame['call'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="mf-error__trace-location"><?= htmlspecialchars($frame['location'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
