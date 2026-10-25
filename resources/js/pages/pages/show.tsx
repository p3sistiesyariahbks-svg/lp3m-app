import * as React from 'react';
import { cn } from 'cn';
import { Head, Link } from '@inertiajs/react';
import {
  CalendarIcon,
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
import { Button, buttonVariants } from '@/components/ui/button';
import { show as pageShow } from '@/routes/pages';
import type { Page } from '@/types/page';

export default function PagesShowPage({ page }: { page: Page }) {
  const content = DOMPurify.sanitize(page.content);

  return (
    <React.Fragment>
      <Head title="Pages" />

      <article className="bg-background border-b-0 py-12 lg:py-16">
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
                <BreadcrumbPage>{page.title}</BreadcrumbPage>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>

          <div className="mx-auto max-w-3xl">
            <header className="w-full text-center">
              <h1 className="text-foreground mb-6 text-3xl font-semibold tracking-tight text-balance sm:text-4xl md:text-5xl md:tracking-tighter">
                {page.title}
              </h1>

              <div className="flex flex-wrap items-center justify-center gap-x-1 gap-y-2">
                <div className="text-muted-foreground flex items-center gap-1.5 text-sm">
                  <UserIcon className="h-4 w-4" /> {page.created_by}
                </div>

                <DotIcon className="text-muted-foreground" />

                <div className="text-muted-foreground flex items-center gap-1.5 text-sm">
                  <CalendarIcon className="size-4" /> {page.published_at}
                </div>
              </div>
            </header>

            <div className="mt-12 flex flex-col gap-6">
              <div
                className="prose prose-lg dark:prose-invert prose-a:text-brand prose-img:rounded-lg max-w-none"
                dangerouslySetInnerHTML={{ __html: content }}
              />
            </div>

            <div className="mt-8 border-t pt-6">
              <p className="text-foreground mb-4 text-sm font-medium">
                Share this page:
              </p>

              <ShareButtons page={page} />
            </div>
          </div>
        </div>
      </article>
    </React.Fragment>
  );
}

function ShareButtons({ page }: { page: Page }) {
  const clipboard = useClipboard({ timeout: 500 });

  const shareButtons = [
    {
      key: 'email',
      label: 'Email',
      ShareButton: EmailShareButton,
      Icon: MailIcon,
      subject: page.title,
      body: '',
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
      text: page.title,
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
      text: page.title,
    },
    {
      key: 'threads',
      label: 'Threads',
      ShareButton: ThreadsShareButton,
      Icon: SiThreads,
      text: page.title,
    },
    {
      key: 'whatsapp',
      label: 'WhatsApp',
      ShareButton: WhatsappShareButton,
      Icon: SiWhatsapp,
      message: page.title,
      separator: ':: ',
    },
    {
      key: 'twitter',
      label: 'Twitter/X',
      ShareButton: XShareButton,
      Icon: SiX,
      text: page.title,
    },
  ] as const;

  return (
    <div className="flex flex-wrap gap-2">
      {shareButtons.map(({ key, label, ShareButton, Icon, ...props }) => (
        <ShareButton
          key={key}
          className={cn(buttonVariants({ size: 'lg' }), 'w-auto')}
          url={pageShow.url(page.slug)}
          resetButtonStyle={false}
          {...props}
        >
          <Icon data-icon="inline-start" /> {label}
        </ShareButton>
      ))}

      <Button
        variant="outline"
        size="lg"
        onClick={() => clipboard.copy(pageShow.url(page.slug))}
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
