// Shared Tailwind CSS Configuration for all pages
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary": "#5d1021",
                "primary-dark": "#3d0a16",
                "primary-dim": "#7a1529",
                "surface-container-low": "#f8fafc",
                "surface-container-high": "#f5f7fb",
                "background": "#ffffff",
                "on-surface": "#1a1c1e",
                "on-background": "#1a1c1e",
                "surface-container": "#f0f2f5",
                "surface-container-highest": "#e8eaed",
                "on-surface-variant": "#44474e",
                "primary-container": "#ffd9dc",
                "on-primary-container": "#40000a",
                "surface-bright": "#f9fafb",
                "outline-variant": "#c4c6d0",
                "outline": "#74777f",
                "secondary": "#775659",
                "tertiary": "#755a2f"
            },
            fontFamily: {
                "headline": ["Plus Jakarta Sans"],
                "body": ["Inter"],
                "label": ["Inter"]
            },
            borderRadius: {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "2xl": "1.5rem",
                "full": "9999px"
            },
        },
    },
};
