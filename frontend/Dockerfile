FROM node:22-alpine

WORKDIR /app

EXPOSE 5173

CMD ["sh", "-c", "if [ ! -d 'node_modules' ] || [ ! -f 'node_modules/.package-lock.json' ]; then echo '📦 Installing dependencies...' && npm install; else echo '✅ node_modules exists — skipping npm install'; fi && npm run dev -- --host 0.0.0.0 --port 5173"]
