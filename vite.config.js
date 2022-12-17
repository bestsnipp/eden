import { resolve } from 'path'
import { defineConfig } from 'vite'

export default defineConfig({
    build: {
        // lib: {
        //     entry: resolve(__dirname, 'src/resources/assets/js/eden.js'),
        //     name: 'Eden',
        //     fileName: 'js/eden',
        // },
        rollupOptions: {
            external: [],
            input: {
                eden: resolve(__dirname, 'src/resources/assets/js/eden.js'),
            },
            output: {
                globals: {},
                dir: 'dist/js',
                entryFileNames: '[name].js'
            },
        },
    },
});
