import type { Announcement } from '@/types/announcement';
import type { Gallery } from '@/types/gallery';
import type { Paginated } from '@/types/pagination';
import type { Media } from '@/types/media';
import type { News } from '@/types/news';

export type Partner = {
  id: string;
  name: string;
  logo: Media;
  url: string;
};

export type PageProps = {
  news: News[];
  photos: Gallery[];
  videos: Gallery[];
  announcements: Paginated<Announcement>;
  partners: Partner[];
};
