'use client';

import * as React from 'react';
import { Head } from '@inertiajs/react';
import { useTheme } from 'next-themes';
import { PDFViewer } from '@/components/extend/pdf-viewer';
import { DocxViewerPreview } from '@/components/extend/docx-viewer';
import { XlsxViewerPreview } from '@/components/extend/xlsx-viewer';
import { PptxViewerPreview } from '@/components/extend/pptx-viewer';
import type { Document } from '@/types/document';
import type { Media } from '@/types/media';

const mimeType = {
  pdf: 'application/pdf',
  doc: 'application/msword',
  docx: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  xls: 'application/vnd.ms-excel',
  xlsx: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  ppt: 'application/vnd.ms-powerpoint',
  pptx: 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
} as const;

function DocumentViewer({ media }: { media: Media }) {
  const { theme, setTheme } = useTheme();

  switch (media.mime_type) {
    case mimeType.pdf:
      return (
        <PDFViewer
          className="absolute inset-0 z-0 flex h-full w-full"
          src={media.url}
          defaultZoom="automatic"
          showDownload
          showToolbar
          showRotateControls
          showUpload={false}
        />
      );
    case mimeType.doc:
    case mimeType.docx:
      return (
        <DocxViewerPreview
          className="absolute inset-0 z-0 flex h-full w-full"
          src={media.url}
          defaultZoom="automatic"
          fileName={media.file_name}
          isDark={theme === 'dark'}
          onIsDarkChange={(dark: boolean) => setTheme(dark ? 'dark' : 'light')}
          showDownload
          showToolbar
          showUpload={false}
        />
      );
    case mimeType.xls:
    case mimeType.xlsx:
      return (
        <XlsxViewerPreview
          className="absolute inset-0 z-0 flex h-full w-full"
          src={media.url}
          fileName={media.file_name}
          isDark={theme === 'dark'}
          onIsDarkChange={(dark: boolean) => setTheme(dark ? 'dark' : 'light')}
          showDownload
          showToolbar
          showUpload={false}
        />
      );
    case mimeType.ppt:
    case mimeType.pptx:
      return (
        <PptxViewerPreview
          className="absolute inset-0 z-0 flex h-full w-full"
          src={media.url}
          defaultThumbnailSidebarOpen={false}
          defaultZoom="fit-page"
          fileName={media.file_name}
          initialSlide={1}
          showDownload
          showToolbar
          showUpload={false}
        />
      );
    default:
      return null;
  }
}

export default function DocumentShowPage({ document }: { document: Document }) {
  return (
    <React.Fragment>
      <Head title="Document Show" />

      <DocumentViewer media={document.document} />
    </React.Fragment>
  );
}

DocumentShowPage.layout = {};
