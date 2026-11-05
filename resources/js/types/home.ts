import type { Announcement } from '@/types/announcement';
import type { Gallery } from '@/types/gallery';
import type { Paginated } from '@/types/pagination';
import type { Media } from '@/types/media';
import type { News } from '@/types/news';
import type { SEO } from '@/types/seo';

export type Partner = {
  id: string;
  name: string;
  logo: Media;
  url: string;
};

export type PageProps = {
  seo: SEO;
  news: News[];
  photos: Gallery[];
  videos: Gallery[];
  announcements: Paginated<Announcement>;
  partners: Partner[];
  [key: string]: unknown;
};
