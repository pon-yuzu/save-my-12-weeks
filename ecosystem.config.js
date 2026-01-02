module.exports = {
  apps: [
    {
      name: 'save-my-12-weeks',
      script: 'node_modules/next/dist/bin/next',
      args: 'start',
      cwd: '/var/www/save-my-12-weeks',
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: '500M',
      env: {
        NODE_ENV: 'production',
        PORT: 3000
      },
      error_file: '/var/log/pm2/save-my-12-weeks-error.log',
      out_file: '/var/log/pm2/save-my-12-weeks-out.log',
      time: true
    }
  ]
};
