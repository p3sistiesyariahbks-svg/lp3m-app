import type { Media } from '@/types/media';

export type StatusType = 'draft' | 'published' | 'scheduled';

export type Page = {
  id: string;
  thumbnail: Media;
  title: string;
  slug: string;
  content: string;
  status: StatusType;
  published_at: string;
  created_by: string;
};
