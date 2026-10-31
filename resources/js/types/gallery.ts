import type { Category } from '@/types/category';
import type { Media } from '@/types/media';
import type { Paginated } from '@/types/pagination';

export type StatusType = 'draft' | 'published' | 'scheduled';

export type GalleryPhoto = {
  id: string;
  alt: string;
  image: Media;
};

export type Gallery = {
  id: string;
  thumbnail: Media;
  title: string;
  slug: string;
  images: GalleryPhoto[];
  video_url: string;
  content: string;
  description: string;
  status: StatusType;
  categories: Category[];
  published_at: string;
  created_by: string;
};

export type Filters = {
  sort_by: string;
};

export type PageProps = {
  galleries: Paginated<Gallery>;
  categories: Category[];
  filters: Filters;
  [key: string]: unknown;
};
