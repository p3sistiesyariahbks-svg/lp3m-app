import * as React from 'react';
import { cn } from 'cn';
import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowRightIcon } from 'lucide-react';
import { buttonVariants } from '@/components/ui/button';
import {
  Marquee,
  MarqueeContent,
  MarqueeEdge,
  MarqueeItem,
} from '@/components/ui/marquee';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { NewsCard } from '@/pages/news/list';
import { index as newsIndex } from '@/routes/news';
import type { PageProps } from '@/types/home';

export default function HomePage() {
  const { news, partners } = usePage<PageProps>().props;

  return (
    <React.Fragment>
      <Head title="Home" />

      <section className="bg-background border-b py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
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

            <div className="mt-4 grid auto-rows-fr gap-8 md:grid-cols-2 lg:grid-cols-4">
              {news.map((news) => (
                <NewsCard key={news.id} news={news} />
              ))}
            </div>
          </div>
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
                Feedback from developers building real products
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
