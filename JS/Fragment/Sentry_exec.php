Sentry.init({
    dsn: 'https://<?php echo $config['sentry_key'] ?>@o<?php echo $config['sentry_n'] ?>.ingest.us.sentry.io/<?php echo $config['sentry_id'] ?>',
    release: "<?php echo $config['site_domain'] ?>@<?php echo $config['version'] ?>",
      integrations: [
        Sentry.browserTracingIntegration(),
        Sentry.replayIntegration(),
      ],

      // Set tracesSampleRate to 1.0 to capture 100%
      // of transactions for performance monitoring.
      // We recommend adjusting this value in production
      tracesSampleRate: 1.0,

      // Capture Replay for 10% of all sessions,
      // plus for 100% of sessions with an error
      replaysSessionSampleRate: 1.0,
      replaysOnErrorSampleRate: 1.0,
});
