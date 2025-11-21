# Multi-stage Dockerfile for Node/Nuxt apps
# Usage examples:
#   Build:  docker build -t stock-master .
#   Run:    docker run -p 3000:3000 stock-master
#   With custom commands:
#     docker build --build-arg BUILD_CMD="npm run build" --build-arg START_CMD="npm run start" -t stock-master .

ARG NODE_VERSION=20-alpine

# 1) Dependencies layer (cached)
FROM node:${NODE_VERSION} AS deps
WORKDIR /app
RUN apk add --no-cache libc6-compat
COPY package*.json ./
RUN npm ci --no-audit --no-fund

# 2) Builder (optional build step)
FROM node:${NODE_VERSION} AS builder
WORKDIR /app
ARG NODE_ENV=production
ENV NODE_ENV=${NODE_ENV}
ARG BUILD_CMD=""
COPY --from=deps /app/node_modules ./node_modules
COPY . .
# If a build command is provided (e.g., Nuxt/Vite), run it; otherwise skip
RUN if [ -n "$BUILD_CMD" ]; then echo "Running build: $BUILD_CMD" && sh -lc "$BUILD_CMD"; else echo "No build step provided"; fi

# 3) Runtime image
FROM node:${NODE_VERSION} AS runner
WORKDIR /app
ENV NODE_ENV=production \
    HOST=0.0.0.0 \
    PORT=3000

# Only copy what's necessary to run
COPY --from=deps /app/node_modules ./node_modules
COPY --from=builder /app .

EXPOSE 3000

# Default start command; override with --build-arg START_CMD or at runtime with CMD
ARG START_CMD="npm run start || npm run dev -- --host 0.0.0.0"
CMD ["sh","-lc","$START_CMD"]


