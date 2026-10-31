import * as React from 'react';
import { cn } from 'cn';
import { Head, Link, InfiniteScroll, usePage } from '@inertiajs/react';
import { ArrowRightIcon, BadgeCheck, ChevronRightIcon } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Spinner } from '@/components/ui/spinner';
import {
  Item,
  ItemActions,
  ItemContent,
  ItemDescription,
  ItemGroup,
  ItemTitle,
} from '@/components/ui/item';
import { Badge } from '@/components/ui/badge';
import { buttonVariants } from '@/components/ui/button';
import {
  Marquee,
  MarqueeContent,
  MarqueeEdge,
  MarqueeItem,
} from '@/components/ui/marquee';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { NumberTicker } from '@/components/ui/number-ticker';
import { NewsCard, NewsEmptyState } from '@/pages/news/list';
import {
  GalleryCard as PhotoCard,
  GalleryEmptyState as PhotoEmptyState,
} from '@/pages/gallery/photos/list';
import {
  GalleryCard as VideoCard,
  GalleryEmptyState as VideoEmptyState,
} from '@/pages/gallery/videos/list';
import { AnnouncementEmptyState } from '@/pages/announcements/show';
import { show as announcementShow } from '@/routes/announcements';
import { index as documentsIndex } from '@/routes/documents';
import { index as newsIndex } from '@/routes/news';
import { index as photosIndex } from '@/routes/gallery/photos';
import { index as videosIndex } from '@/routes/gallery/videos';
import type { PageProps } from '@/types/home';

const statistics = [
  { label: 'Research', value: 125 },
  { label: 'Community Service', value: 85 },
  { label: 'Scientific Publications', value: 240 },
  { label: 'Collaborations', value: 32 },
] as const;

export default function HomePage() {
  const { news, photos, videos, announcements, partners } =
    usePage<PageProps>().props;

  return (
    <React.Fragment>
      <Head title="Home" />

      <section className="bg-muted/30 relative isolate flex min-h-screen items-center justify-center overflow-hidden border-b py-12 lg:py-16">
        <div className="pointer-events-none absolute inset-0 -z-10 [background-image:radial-gradient(var(--border)_2px,transparent_2px)] [mask-image:radial-gradient(ellipse_at_center,black,transparent_75%)] [background-size:24px_24px]" />

        <div className="mx-auto w-full max-w-(--breakpoint-xl) px-4 py-10 sm:px-6 lg:px-8 lg:py-16">
          <div className="relative z-10 mx-auto max-w-4xl text-center">
            <Badge
              className="border-border rounded-full py-1"
              variant="secondary"
            >
              <BadgeCheck data-icon="inline-start" />
              Official Portal
            </Badge>

            <h1 className="mx-auto mt-6 max-w-4xl text-4xl font-medium tracking-tight sm:text-[2.75rem] md:text-6xl/[1.2]">
              Research and Community Service
            </h1>

            <p className="text-muted-foreground mx-auto mt-6 max-w-2xl text-xl md:text-2xl/normal">
              Advancing knowledge and serving communities through research and
              collaboration.
            </p>

            <div className="mt-12 flex items-center justify-center gap-4">
              <Link
                href={documentsIndex()}
                className={cn(
                  buttonVariants({ size: 'lg' }),
                  'h-10 rounded-full px-5',
                )}
              >
                Lihat Dokumen
              </Link>

              <Link
                href={newsIndex()}
                className={cn(
                  buttonVariants({ size: 'lg', variant: 'outline' }),
                  'h-10 rounded-full px-5',
                )}
              >
                Latest News
              </Link>
            </div>
          </div>
        </div>
      </section>

      <section className="bg-background border-b py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="m-0 block h-auto w-full p-0">
            <div className="flex flex-col items-center justify-between gap-4">
              <h2 className="text-center text-4xl font-medium tracking-tight md:text-4xl">
                Our Impact
              </h2>
              <p className="text-muted-foreground mt-2 text-center text-xl md:text-2xl">
                Summary of research, community service, scientific publications,
                and collaborations.
              </p>
            </div>

            <div className="mt-14 grid auto-rows-fr gap-8 md:grid-cols-2 lg:grid-cols-4">
              {statistics.map((statistic) => (
                <div
                  key={statistic.label}
                  className="bg-muted/30 min-w-0 rounded-xl border p-6"
                >
                  <NumberTicker
                    value={statistic.value}
                    startValue={0}
                    className="text-5xl font-medium tracking-tighter whitespace-pre-wrap"
                  >
                    {statistic.value}
                  </NumberTicker>

                  <p className="text-foreground/80 mt-4 text-lg">
                    {statistic.label}
                  </p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section className="bg-muted/30 border-b py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="flex w-full min-w-0 flex-1 flex-col gap-12">
            <div className="m-0 block h-auto w-full p-0">
              <div className="flex items-center justify-between gap-4">
                <h2 className="truncate text-[1.5rem] font-semibold tracking-tight">
                  News
                </h2>

                <Link
                  href={newsIndex()}
                  className={cn(
                    buttonVariants({ size: 'lg', variant: 'ghost' }),
                    'w-auto',
                  )}
                >
                  View All
                  <ArrowRightIcon data-icon="inline-end" />
                </Link>
              </div>

              {news.length > 0 ? (
                <div className="mt-4 grid auto-rows-fr gap-8 md:grid-cols-2 lg:grid-cols-3">
                  {news.map((news) => (
                    <NewsCard key={news.id} news={news} />
                  ))}
                </div>
              ) : (
                <NewsEmptyState />
              )}
            </div>

            <div className="m-0 block h-auto w-full p-0">
              <div className="flex items-center justify-between gap-4">
                <h2 className="truncate text-[1.5rem] font-semibold tracking-tight">
                  Photos
                </h2>

                <Link
                  href={photosIndex()}
                  className={cn(
                    buttonVariants({ size: 'lg', variant: 'ghost' }),
                    'w-auto',
                  )}
                >
                  View All
                  <ArrowRightIcon data-icon="inline-end" />
                </Link>
              </div>

              {photos.length > 0 ? (
                <div className="mt-4 grid auto-rows-fr gap-8 md:grid-cols-2 lg:grid-cols-3">
                  {photos.map((photo) => (
                    <PhotoCard key={photo.id} gallery={photo} />
                  ))}
                </div>
              ) : (
                <PhotoEmptyState />
              )}
            </div>

            <div className="m-0 block h-auto w-full p-0">
              <div className="flex items-center justify-between gap-4">
                <h2 className="truncate text-[1.5rem] font-semibold tracking-tight">
                  Videos
                </h2>

                <Link
                  href={videosIndex()}
                  className={cn(
                    buttonVariants({ size: 'lg', variant: 'ghost' }),
                    'w-auto',
                  )}
                >
                  View All
                  <ArrowRightIcon data-icon="inline-end" />
                </Link>
              </div>

              {videos.length > 0 ? (
                <div className="mt-4 grid auto-rows-fr gap-8 md:grid-cols-2 lg:grid-cols-3">
                  {videos.map((video) => (
                    <VideoCard key={video.id} gallery={video} />
                  ))}
                </div>
              ) : (
                <VideoEmptyState />
              )}
            </div>
          </div>

          <aside className="sticky top-24 w-full shrink-0 lg:max-w-80">
            <Card className="gap-4 p-6">
              <CardHeader className="p-0">
                <CardTitle className="text-xl font-medium tracking-tight">
                  Announcements
                </CardTitle>
              </CardHeader>

              <CardContent className="p-0">
                {announcements.data.length > 0 ? (
                  <ScrollArea className="[&_[data-slot=scroll-area-viewport]]:scroll-fade-y h-96">
                    <InfiniteScroll
                      data="announcements"
                      onlyNext
                      preserveUrl
                      next={({ loading }) =>
                        loading && (
                          <div className="flex justify-center py-4">
                            <Spinner className="size-4" />
                          </div>
                        )
                      }
                    >
                      <ItemGroup className="gap-2">
                        {announcements.data.map((announcement) => (
                          <Item
                            key={announcement.id}
                            variant="outline"
                            render={
                              <Link href={announcementShow(announcement.slug)}>
                                <ItemContent>
                                  <ItemTitle className="line-clamp-none">
                                    {announcement.title}
                                  </ItemTitle>
                                  <ItemDescription>
                                    {announcement.published_at}
                                  </ItemDescription>
                                </ItemContent>

                                <ItemActions>
                                  <ChevronRightIcon className="size-4" />
                                </ItemActions>
                              </Link>
                            }
                          />
                        ))}
                      </ItemGroup>
                    </InfiniteScroll>
                  </ScrollArea>
                ) : (
                  <AnnouncementEmptyState />
                )}
              </CardContent>
            </Card>
          </aside>
        </div>
      </section>

      <section className="bg-background py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="m-0 block h-auto w-full p-0">
            <div className="flex flex-col items-center justify-between gap-4">
              <h2 className="text-center text-4xl font-medium tracking-tight md:text-4xl">
                Partners
              </h2>
              <p className="text-muted-foreground mt-2 text-center text-xl md:text-2xl">
                Working together to advance research and serve communities.
              </p>
            </div>

            <Marquee
              className="relative z-0 mt-14 w-full"
              aria-label="Partners"
              pauseOnHover
              pauseOnKeyboard
            >
              <MarqueeContent>
                {partners.map((partner) => (
                  <MarqueeItem key={partner.id}>
                    <Link
                      href={partner.url}
                      className="bg-card text-card-foreground flex w-48 flex-col gap-1 rounded-md border p-2 shadow-none"
                      target="_blank"
                    >
                      <AspectRatio
                        ratio={16 / 9}
                        className="relative w-full overflow-hidden border-none"
                      >
                        <img
                          className="bg-background size-full object-contain"
                          sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                          src={partner.logo.url}
                          alt={partner.name}
                        />
                      </AspectRatio>
                    </Link>
                  </MarqueeItem>
                ))}
              </MarqueeContent>
              <MarqueeEdge side="left" />
              <MarqueeEdge side="right" />
            </Marquee>
          </div>
        </div>
      </section>
    </React.Fragment>
  );
}
