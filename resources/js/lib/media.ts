'use client';

import { createMedia } from '@artsy/fresnel';

export const { MediaContextProvider, Media, createMediaStyle } = createMedia({
  breakpoints: {
    xs: 0,
    sm: 640,
    md: 768,
    lg: 1024,
    xl: 1280,
  },
});

export const mediaStyle = createMediaStyle();
