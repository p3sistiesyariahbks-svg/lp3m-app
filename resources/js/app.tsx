import { createInertiaApp } from '@inertiajs/react';
import { ThemeProvider } from '@/components/theme-provider';
import MainLayout from '@/layouts/main';
import { MediaContextProvider } from '@/lib/media';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
  strictMode: true,
  title: (title) => title || appName,
  progress: {
    color: '#4B5563',
  },
  layout: () => MainLayout,
  withApp(app) {
    return (
      <ThemeProvider
        attribute="class"
        storageKey="theme"
        defaultTheme="system"
        enableSystem
        disableTransitionOnChange
      >
        <MediaContextProvider>{app}</MediaContextProvider>
      </ThemeProvider>
    );
  },
});
