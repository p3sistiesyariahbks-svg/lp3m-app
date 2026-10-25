import type { Category } from '@/types/category';
import type { Media } from '@/types/media';
import type { Paginated } from '@/types/pagination';

export type StatusType = 'draft' | 'published' | 'scheduled';

export type Document = {
  id: string;
  thumbnail: Media;
  title: string;
  slug: string;
  document: Media;
  file_url: string;
  status: StatusType;
  categories: Category[];
  published_at: string;
  created_by: string;
};

export type Filters = {
  sort_by: string;
};

export type PageProps = {
  documents: Paginated<Document>;
  categories: Category[];
  filters: Filters;
  [key: string]: unknown;
};
