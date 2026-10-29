import * as React from 'react';
import { cn } from 'cn';
import { Head, Link, InfiniteScroll, router, usePage } from '@inertiajs/react';
import { CalendarIcon, DotIcon, UserIcon, ImagesIcon } from 'lucide-react';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from '@/components/ui/empty';
import {
  index as photoIndex,
  category as photoCategory,
  show as photoShow,
} from '@/routes/gallery/photos';
import type { Gallery, PageProps } from '@/types/gallery';
import type { Category } from '@/types/category';

export const sorts = [
  { label: 'Latest', value: 'latest' },
  { label: 'Oldest', value: 'oldest' },
  { label: 'Title (A-Z)', value: 'title_asc' },
  { label: 'Title (Z-A)', value: 'title_desc' },
  { label: 'Most Viewed', value: 'popular' },
] as const;

export default function GalleryPhotoListPage() {
  const { galleries, categories, filters } = usePage<PageProps>().props;

  function handleSortBy(value: string | null) {
    router.get(
      photoIndex(),
      { sort_by: value },
      {
        only: ['galleries', 'filters'],
        preserveState: true,
        preserveScroll: true,
        preserveUrl: true,
        replace: true,
        reset: ['galleries'],
      },
    );
  }

  return (
    <React.Fragment>
      <Head title="Photos" />

      <section className="bg-muted border-b py-12 lg:py-16">
        <div className="mx-auto max-w-(--breakpoint-xl) px-4 sm:px-6 lg:px-8">
          <div className="mx-auto max-w-3xl text-center">
            <h1 className="mb-4 text-4xl font-bold tracking-tight">Photos</h1>

            <p className="text-muted-foreground mt-0 text-lg">
              Documentation of research and community service activities
            </p>
          </div>
        </div>
      </section>

      <section className="bg-background py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="m-0 block h-auto w-full p-0">
            <div className="flex items-center justify-between gap-4">
              <h2 className="truncate text-[1.5rem] font-semibold tracking-tight">
                Photos
              </h2>

              {galleries.data.length > 0 && (
                <Select
                  items={sorts}
                  value={filters.sort_by || sorts[0].value}
                  onValueChange={handleSortBy}
                >
                  <SelectTrigger className="w-full max-w-[180px]">
                    <SelectValue placeholder="Sort by" />
                  </SelectTrigger>
                  <SelectContent alignItemWithTrigger={false}>
                    <SelectGroup>
                      <SelectLabel>Sort By</SelectLabel>
                      {sorts.map((sort) => (
                        <SelectItem key={sort.value} value={sort.value}>
                          {sort.label}
                        </SelectItem>
                      ))}
                    </SelectGroup>
                  </SelectContent>
                </Select>
              )}
            </div>

            {galleries.data.length > 0 ? (
              <InfiniteScroll
                data="galleries"
                preserveUrl
                onlyNext
                manualAfter={3}
                next={({ loading, fetch, hasMore }) =>
                  hasMore && (
                    <div className="mt-16 flex items-center justify-between gap-4">
                      <Button
                        className="mx-auto"
                        size="lg"
                        variant="secondary"
                        onClick={fetch}
                        disabled={loading}
                      >
                        {loading && <Spinner data-icon="inline-start" />}
                        Load more photos
                      </Button>
                    </div>
                  )
                }
              >
                <div className="mt-4 grid auto-rows-fr gap-8 md:grid-cols-2 lg:grid-cols-3">
                  {galleries.data.map((gallery) => (
                    <GalleryCard key={gallery.id} gallery={gallery} />
                  ))}
                </div>
              </InfiniteScroll>
            ) : (
              <GalleryEmptyState />
            )}
          </div>

          {categories.length > 0 && (
            <aside className="sticky top-8 w-full shrink-0 lg:max-w-80">
              <Card className="gap-4 p-6">
                <h3 className="text-xl font-medium tracking-tight">
                  Categories
                </h3>

                <ul className="mt-0 space-y-1">
                  {categories.map((category) => (
                    <li key={category.id}>
                      <CategoryItem category={category} />
                    </li>
                  ))}
                </ul>
              </Card>
            </aside>
          )}
        </div>
      </section>
    </React.Fragment>
  );
}

export function GalleryCard({ gallery }: { gallery: Gallery }) {
  return (
    <div className="group flex w-full flex-col text-start transition-all duration-300 hover:-translate-y-1 hover:shadow-none">
      <Link href={photoShow(gallery.slug)} className="relative" prefetch>
        <AspectRatio
          ratio={16 / 9}
          className="relative mb-0 w-full overflow-hidden rounded-xl"
        >
          <img
            className="bg-muted size-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-110"
            sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
            src={gallery.thumbnail.url}
            alt={gallery.title}
          />
        </AspectRatio>
      </Link>

      <div className="px-1">
        <div className="mt-4 flex flex-wrap items-center gap-2">
          {gallery.categories.map((category) => (
            <Badge
              key={category.id}
              render={
                <Link
                  href={photoCategory(category.slug)}
                  className="relative"
                  prefetch
                >
                  {category.name}
                </Link>
              }
            />
          ))}

          <span className="text-muted-foreground text-xs font-medium">
            {gallery.images.length} images
          </span>
        </div>

        <h3 className="mt-3 line-clamp-2 text-[1.35rem] font-medium tracking-[-0.015em] text-pretty">
          <Link href={photoShow(gallery.slug)} className="relative" prefetch>
            {gallery.title}
          </Link>
        </h3>

        <div className="mt-4 flex items-center justify-start gap-1">
          <span className="text-muted-foreground flex items-center gap-1.5 text-sm">
            <UserIcon className="h-4 w-4" /> {gallery.created_by}
          </span>

          <DotIcon className="text-muted-foreground" />

          <span className="text-muted-foreground flex items-center gap-1.5 text-sm">
            <CalendarIcon className="size-4" /> {gallery.published_at}
          </span>
        </div>
      </div>
    </div>
  );
}

export function GalleryEmptyState() {
  return (
    <Empty className="mt-4 border border-solid">
      <EmptyHeader>
        <EmptyMedia variant="icon">
          <ImagesIcon />
        </EmptyMedia>
        <EmptyTitle>No photos found</EmptyTitle>
        <EmptyDescription>
          There are no photos to show here yet.
        </EmptyDescription>
      </EmptyHeader>
    </Empty>
  );
}

export function CategoryItem({ category }: { category: Category }) {
  const { url } = usePage();

  return (
    <React.Fragment>
      <Link
        href={photoCategory(category.slug)}
        className={cn(
          'focus-visible:border-ring focus-visible:ring-ring/50 [a]:hover:bg-muted flex w-full flex-wrap items-center gap-2 rounded-lg border border-transparent px-2.5 py-2 text-sm transition-colors duration-100 outline-none focus-visible:ring-[3px] [a]:transition-colors',
          {
            'bg-muted/50 hover:bg-muted focus:bg-muted': url.startsWith(
              photoCategory(category.slug).url,
            ),
          },
        )}
        prefetch
        preserveScroll
      >
        <div className="flex flex-1 flex-col gap-1">
          <div className="text-muted-foreground line-clamp-1 flex w-fit items-center gap-2 text-sm leading-snug font-normal underline-offset-4">
            {category.name}
          </div>
        </div>
      </Link>

      {category.children.length > 0 && (
        <ul className="mt-0 ml-2 space-y-1 pl-2">
          {category.children.map((grandchild) => (
            <li key={grandchild.id}>
              <CategoryItem category={grandchild} />
            </li>
          ))}
        </ul>
      )}
    </React.Fragment>
  );
}
