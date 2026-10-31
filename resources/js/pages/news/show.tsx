import * as React from 'react';
import { cn } from 'cn';
import { Head, Link } from '@inertiajs/react';
import {
  ArrowRightIcon,
  CalendarIcon,
  ClockIcon,
  CopyIcon,
  DotIcon,
  MailIcon,
  UserIcon,
} from 'lucide-react';
import {
  SiFacebook,
  SiLine,
  SiLinkedin,
  SiTelegram,
  SiThreads,
  SiWhatsapp,
  SiX,
} from '@icons-pack/react-simple-icons';
import {
  EmailShareButton,
  FacebookShareButton,
  LineShareButton,
  LinkedinShareButton,
  TelegramShareButton,
  ThreadsShareButton,
  WhatsappShareButton,
  XShareButton,
} from 'react-share';
import { useClipboard } from '@mantine/hooks';
import DOMPurify from 'isomorphic-dompurify';
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { Badge } from '@/components/ui/badge';
import { Button, buttonVariants } from '@/components/ui/button';
import { NewsCard } from '@/pages/news/list';
import {
  category as newsCategory,
  index as newsIndex,
  show as newsShow,
} from '@/routes/news';
import type { News } from '@/types/news';

export default function NewsShowPage({
  news,
  suggestions,
}: {
  news: News;
  suggestions: News[];
}) {
  const content = DOMPurify.sanitize(news.content);
  const [category] = news.categories;

  return (
    <React.Fragment>
      <Head title="News Show" />

      <article className="bg-background border-b py-12 lg:py-16">
        <div className="mx-auto max-w-(--breakpoint-xl) px-4 sm:px-6 lg:px-8">
          <Breadcrumb className="mb-8">
            <BreadcrumbList>
              <BreadcrumbItem>
                <BreadcrumbLink
                  render={
                    <Link href="/" className="relative">
                      Home
                    </Link>
                  }
                />
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbLink
                  render={
                    <Link href={newsIndex()} className="relative">
                      News
                    </Link>
                  }
                />
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbLink
                  render={
                    <Link
                      href={newsCategory(category.slug)}
                      className="relative"
                    >
                      {category.name}
                    </Link>
                  }
                />
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbPage>{news.title}</BreadcrumbPage>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>

          <div className="mx-auto max-w-3xl">
            <header className="w-full text-center">
              <div className="items-ceneter mb-4 flex flex-wrap justify-center gap-2">
                {news.categories.map((category) => (
                  <Badge
                    key={category.id}
                    render={
                      <Link
                        href={newsCategory(category.slug)}
                        className="relative"
                      >
                        {category.name}
                      </Link>
                    }
                  />
                ))}
              </div>

              <h1 className="text-foreground mb-6 text-3xl font-semibold tracking-tight text-balance sm:text-4xl md:text-5xl md:tracking-tighter">
                {news.title}
              </h1>

              <div className="flex flex-wrap items-center justify-center gap-x-1 gap-y-2">
                <div className="text-muted-foreground flex items-center gap-1.5 text-sm">
                  <UserIcon className="h-4 w-4" /> {news.created_by}
                </div>

                <DotIcon className="text-muted-foreground" />

                <div className="text-muted-foreground flex items-center gap-1.5 text-sm">
                  <CalendarIcon className="size-4" /> {news.published_at}
                </div>

                <DotIcon className="text-muted-foreground" />

                <div className="text-muted-foreground flex items-center gap-1.5 text-sm">
                  <ClockIcon className="size-4" /> {news.reading_time}
                </div>
              </div>
            </header>

            <AspectRatio
              ratio={16 / 9}
              className="mt-12 overflow-hidden rounded-2xl border"
            >
              <img
                className="bg-muted size-full object-cover"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                src={news.thumbnail.url}
                alt={news.title}
              />
            </AspectRatio>

            <div className="mt-12 flex flex-col gap-6">
              <div
                className="prose prose-lg dark:prose-invert prose-a:text-brand prose-img:rounded-lg max-w-none"
                dangerouslySetInnerHTML={{ __html: content }}
              />
            </div>

            <div className="mt-8 border-t pt-6">
              <p className="text-foreground mb-4 text-sm font-medium">
                Share this news:
              </p>

              <ShareButtons news={news} />
            </div>
          </div>
        </div>
      </article>

      <section className="bg-background py-12 lg:py-16">
        <div className="mx-auto flex max-w-(--breakpoint-xl) flex-col items-start gap-12 px-4 py-10 sm:px-6 lg:flex-row lg:px-8 lg:py-16">
          <div className="m-0 block h-auto w-full p-0">
            <div className="flex items-center justify-between gap-4">
              <h2 className="truncate text-[1.5rem] font-semibold tracking-tight">
                Related News
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
              {suggestions.map((suggestion) => (
                <NewsCard key={suggestion.id} news={suggestion} />
              ))}
            </div>
          </div>
        </div>
      </section>
    </React.Fragment>
  );
}

function ShareButtons({ news }: { news: News }) {
  const clipboard = useClipboard({ timeout: 500 });

  const shareButtons = [
    {
      key: 'email',
      label: 'Email',
      ShareButton: EmailShareButton,
      Icon: MailIcon,
      subject: news.title,
      body: news.description,
    },
    {
      key: 'facebook',
      label: 'Facebook',
      ShareButton: FacebookShareButton,
      Icon: SiFacebook,
    },
    {
      key: 'line',
      label: 'Line',
      ShareButton: LineShareButton,
      Icon: SiLine,
      text: news.title,
    },
    {
      key: 'linkedin',
      label: 'LinkedIn',
      ShareButton: LinkedinShareButton,
      Icon: SiLinkedin,
    },
    {
      key: 'telegram',
      label: 'Telegram',
      ShareButton: TelegramShareButton,
      Icon: SiTelegram,
      text: news.title,
    },
    {
      key: 'threads',
      label: 'Threads',
      ShareButton: ThreadsShareButton,
      Icon: SiThreads,
      text: news.title,
    },
    {
      key: 'whatsapp',
      label: 'WhatsApp',
      ShareButton: WhatsappShareButton,
      Icon: SiWhatsapp,
      message: news.title,
      separator: ':: ',
    },
    {
      key: 'twitter',
      label: 'Twitter/X',
      ShareButton: XShareButton,
      Icon: SiX,
      text: news.title,
    },
  ] as const;

  return (
    <div className="flex flex-wrap gap-2">
      {shareButtons.map(({ key, label, ShareButton, Icon, ...props }) => (
        <ShareButton
          key={key}
          className={cn(buttonVariants({ size: 'lg' }), 'w-auto')}
          url={newsShow.url(news.slug)}
          resetButtonStyle={false}
          {...props}
        >
          <Icon data-icon="inline-start" /> {label}
        </ShareButton>
      ))}

      <Button
        variant="outline"
        size="lg"
        onClick={() => clipboard.copy(newsShow.url(news.slug))}
      >
        {clipboard.copied ? (
          'Copied!'
        ) : (
          <React.Fragment>
            <CopyIcon data-icon="inline-start" /> Copy Link
          </React.Fragment>
        )}
      </Button>
    </div>
  );
}
