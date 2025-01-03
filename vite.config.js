import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path"; // <-- require path from node

export default defineConfig({
    plugins: [
        laravel({
            // edit the first value of the array input to point to our new sass files and folder.
            input: ["resources/scss/app.scss", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    // Add resolve object and aliases
    resolve: {
        alias: {
            "~icons": path.resolve(
                __dirname,
                "node_modules/bootstrap-icons/font"
            ),
            "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
            "~@fortawesome": path.resolve(
                __dirname,
                "node_modules/@fortawesome"
            ),

            "~resources": path.resolve(__dirname, "resources"),
        },
    },
   
    build: {
        // Opzione per forzare gli asset generati a essere in HTTPS in produzione
        assetPublicPath: "/build/", // Non dovresti aver bisogno di modificarlo se stai usando il path corretto in Laravel
    },
});
