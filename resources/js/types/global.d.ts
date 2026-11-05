import type { Auth } from '@/types/auth';
import type { Menu } from '@/types/layout';
import type {
  AppearanceSettings,
  GeneralSettings,
  SocialLinksSettings,
} from '@/types/settings';

declare module 'react' {
  interface InputHTMLAttributes<T> {
    passwordrules?: string;
  }
}

declare module '@inertiajs/core' {
  export interface InertiaConfig {
    sharedPageProps: {
      name: string;
      auth: Auth;
      menu: {
        header: Menu;
        footer: Menu;
      };
      settings: {
        general: GeneralSettings;
        socialLinks: SocialLinksSettings;
        appearance: AppearanceSettings;
      };
      [key: string]: unknown;
    };
  }
}
