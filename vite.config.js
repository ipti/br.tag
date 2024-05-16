import {defineConfig, loadEnv} from 'vite';
import liveReload from 'vite-plugin-live-reload';
import dotenv from 'dotenv';
import fs from 'fs';

export default ({mode}) => {
    Object.assign(process.env, dotenv.parse(fs.readFileSync(`${__dirname}/.env`)));
    const port = process.env.VITE_PORT;
    const origin = `${process.env.VITE_ORIGIN}:${port}`;
    return defineConfig({
        plugins: [
            // register live reload plugin, for refreshing the browser on file changes
            liveReload([
                __dirname + '/templates/**/*.php',
            ]),
        ],
        base: '/',
        // config for the build
        build: {
            manifest: "manifest.json",
            outDir: 'web/resources/',
            rollupOptions: {
                input: {
                    js: './js/',
                    css: './sass/scss/',
                },
                output: {
                    entryFileNames: `bundle/main.js`,
                    /* assetFileNames: `[ext]/app.[ext]`, */
                    assetFileNames: ({name}) => {

                        if (/\.(gif|jpe?g|png|svg)$/.test(name ?? '')){
                            return 'images/[name].[ext]';
                        }

                        if (/\.css$/.test(name ?? '')) {
                            return 'sass/css/main.[ext]';
                        }

                        return '[name].[ext]';
                      },
                },
            },
        },
        // config for the dev server
        server: {
            // force to use the port from the .env file
            strictPort: true,
            port: port,

            // define source of the images
            origin: origin,
            hmr: {
                host: 'localhost',
            },
        },
        resolve: {
            alias: {
                vue: 'vue/dist/vue.esm-bundler.js'
            }
        }
    })
}
