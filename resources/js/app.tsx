import { createInertiaApp } from '@inertiajs/react';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    // Judul tiap halaman sudah memuat nama brand, jadi tidak ditempeli lagi.
    title: (title) => title || appName,
    progress: {
        color: '#4B5563',
    },
});
