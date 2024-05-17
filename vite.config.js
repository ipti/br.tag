import {defineConfig, loadEnv} from 'vite';
import liveReload from 'vite-plugin-live-reload';
import dotenv from 'dotenv';
import fs from 'fs';

export default ({mode}) => {
    // Object.assign(process.env, dotenv.parse(fs.readFileSync(`${__dirname}`)));


    // const port = process.env.VITE_PORT;
    const port = 433;
    // const origin = `${process.env.VITE_ORIGIN}:${port}`;
    const origin = '433:433';
    return defineConfig({
        plugins: [
            // register live reload plugin, for refreshing the browser on file changes
            liveReload([
                __dirname + '/**/*.php',
            ]),
        ],
        base: '/',
        // config for the build
        build: {
            manifest: "manifest.json",
            outDir: 'web/resources/',
            rollupOptions: {
                input: {
                    js: '/app/js/admin/auditory.js',
                    js2: '/app/js/admin/instance-config.js',
                    // css: '/app/sass/scss/_helper.scss',
                },
                output: {
                    entryFileNames: `entry[name].js`,
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
