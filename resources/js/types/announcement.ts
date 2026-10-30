export type StatusType = 'draft' | 'published' | 'scheduled';

export type Announcement = {
  id: string;
  title: string;
  slug: string;
  content: string;
  status: StatusType;
  published_at: string;
  created_by: string;
};
