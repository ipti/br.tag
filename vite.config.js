import {defineConfig, loadEnv} from 'vite';
import liveReload from 'vite-plugin-live-reload';
import { globSync } from 'glob';
import dotenv from 'dotenv';
import fs from 'fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

export default ({mode}) => {
    // Object.assign(process.env, dotenv.parse(fs.readFileSync(`${__dirname}`)));

    // const port = process.env.VITE_PORT;
    const port = 433;
    // const origin = `${process.env.VITE_ORIGIN}:${port}`;
    const origin = '433:433';

    const jsFiles = globSync('themes/default/js/dependencies/**/*.js').map(file => {
        // Create file URL from the path
        return path.resolve(file);
    });

    // Create a single entry point file that imports all other JS files
    const entryDirPath = path.resolve('themes/default/js');
    const entryFilePath = path.join(entryDirPath, 'index.js');

    // Ensure the directory exists
    if (!fs.existsSync(entryDirPath)) {
        fs.mkdirSync(entryDirPath, { recursive: true });
    }


    const entryFileContent = jsFiles.map(file => `import '${file.replace(/\\/g, '/')}';`).join('\n');
    require('fs').writeFileSync(entryFilePath, entryFileContent);

    console.log('Entry file created with content:\n', entryFileContent);

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
            // outDir: 'web/resources/',
            // filename: 'bundle',
            emptyOutDir: false,
            rollupOptions: {
                input: entryFilePath,
                output: {
                    inlineDynamicImports: false,
                    entryFileNames: `bundle.js`,
                    format: 'es',
                    dir: path.resolve('web/resources'),
                    manualChunks: {},
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


