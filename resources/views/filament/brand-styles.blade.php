<style>
    :root{
        /* Primary palette derived from admin setting */
        --primary-50:  color-mix(in oklab, {{ $primary }} 10%, white);
        --primary-100: color-mix(in oklab, {{ $primary }} 20%, white);
        --primary-200: color-mix(in oklab, {{ $primary }} 30%, white);
        --primary-300: color-mix(in oklab, {{ $primary }} 40%, white);
        --primary-400: color-mix(in oklab, {{ $primary }} 60%, white);
        --primary-500: {{ $primary }};
        --primary-600: color-mix(in oklab, {{ $primary }} 85%, black);
        --primary-700: color-mix(in oklab, {{ $primary }} 75%, black);
        --primary-800: color-mix(in oklab, {{ $primary }} 65%, black);
        --primary-900: color-mix(in oklab, {{ $primary }} 55%, black);
        --primary-950: color-mix(in oklab, {{ $primary }} 45%, black);

        /* Optional: map "secondary" into the support palette if needed later */
        --info-500: {{ $secondary }};
    }
</style>
