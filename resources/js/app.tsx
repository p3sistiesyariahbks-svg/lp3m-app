import { createInertiaApp } from '@inertiajs/react';
import { ThemeProvider } from '@/components/theme-provider';
import MainLayout from '@/layouts/main';
import { MediaContextProvider } from '@/lib/media';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
  strictMode: true,
  title: (title) => title || appName,
  progress: {
    color: 'var(--primary)',
  },
  layout: () => MainLayout,
  withApp(app, { page }) {
    const {
      settings: { appearance },
    } = page.props;

    return (
      <ThemeProvider
        attribute="class"
        storageKey="theme"
        defaultTheme={appearance.default_theme}
        enableSystem
        disableTransitionOnChange
      >
        <MediaContextProvider>{app}</MediaContextProvider>
      </ThemeProvider>
    );
  },
});
