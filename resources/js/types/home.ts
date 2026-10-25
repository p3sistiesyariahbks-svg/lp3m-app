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
  partners: Partner[];
};
