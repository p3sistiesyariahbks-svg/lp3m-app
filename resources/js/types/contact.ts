import { LucideIcon } from 'lucide-react';
import type { SEO } from '@/types/seo';
import type { GeneralSettings } from '@/types/settings';

export type ContactInfo = {
  icon: LucideIcon;
  title: string;
  href: string;
  label: React.ReactNode;
};

export type PageProps = {
  seo: SEO;
  settings: {
    general: GeneralSettings;
  };
  [key: string]: unknown;
};
