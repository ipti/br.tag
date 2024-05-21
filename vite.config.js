import {defineConfig, loadEnv} from 'vite';
import liveReload from 'vite-plugin-live-reload';
import { globSync } from 'glob';
import dotenv from 'dotenv';
import fs from 'fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import commonjs from '@rollup/plugin-commonjs';


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
            filename: 'bundle',
            rollupOptions: {
                input: Object.fromEntries(
                    globSync('/app/js/**/*.js').map(file => [
                        // This remove `src/` as well as the file extension from each
                        // file, so e.g. src/nested/foo.js becomes nested/foo
                        path.relative(
                            'app/js',
                            file.slice(0, file.length - path.extname(file).length)
                        ),
                        // This expands the relative paths to absolute paths, so e.g.
                        // src/nested/foo becomes /project/src/nested/foo.js
                        fileURLToPath(new URL(file, import.meta.url))
                    ])
                ),
                output: {
                    entryFileNames: `_[name].js`,
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
        }
    })
}


