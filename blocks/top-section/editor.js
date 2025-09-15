/* global wp */
(function() {
  const { registerBlockType } = wp.blocks;
  const { __ } = wp.i18n;
  const { MediaUpload, InspectorControls, RichText, URLInput } = wp.blockEditor || wp.editor;
  const { PanelBody, Button, RangeControl, TextControl, SelectControl } = wp.components;
  const el = wp.element.createElement;

  function Edit(props) {
    const { attributes, setAttributes, className } = props;
    const {
      imageId, imageUrl,
      backgroundPosition, backgroundSize,
      title, content,
      buttonText, buttonUrl, buttonTarget,
      boxBgOpacity, boxRadius,
    } = attributes;

    const onSelectImage = (media) => {
      setAttributes({ imageId: media.id, imageUrl: media.url, imageAlt: media.alt });
    };

    const bgStyle = {
      backgroundImage: imageUrl ? 'url(' + imageUrl + ')' : undefined,
      backgroundPosition,
      backgroundSize,
    };

    const cardStyle = {
      backgroundColor: 'rgba(255,255,255,' + ((boxBgOpacity ?? 0.9)) + ')',
      borderRadius: boxRadius ? (boxRadius + 'px') : undefined,
    };

    return el(
      wp.element.Fragment,
      null,
      el(
        InspectorControls,
        null,
        el(
          PanelBody,
          { title: __('Tło', 'mroomy_s'), initialOpen: true },
          el(MediaUpload, {
            onSelect: onSelectImage,
            allowedTypes: ['image'],
            value: imageId,
            render: ({ open }) => el(Button, { onClick: open, variant: 'secondary' }, imageUrl ? __('Zmień obraz', 'mroomy_s') : __('Wybierz obraz', 'mroomy_s'))
          }),
          el(TextControl, {
            label: __('Pozycja tła (CSS)', 'mroomy_s'),
            value: backgroundPosition,
            onChange: (v) => setAttributes({ backgroundPosition: v }),
            help: 'np. 0.92% 53.06% lub center'
          }),
          el(SelectControl, {
            label: __('Rozmiar tła', 'mroomy_s'),
            value: backgroundSize,
            options: [
              { label: 'cover', value: 'cover' },
              { label: 'contain', value: 'contain' },
              { label: 'auto', value: 'auto' },
              { label: '115% 119% (figma)', value: '115% 119%' },
            ],
            onChange: (v) => setAttributes({ backgroundSize: v })
          })
        ),
        el(
          PanelBody,
          { title: __('Karta treści', 'mroomy_s'), initialOpen: true },
          el(RangeControl, {
            label: __('Nieprzezroczystość tła', 'mroomy_s'),
            min: 0, max: 1, step: 0.05,
            value: boxBgOpacity,
            onChange: (v) => setAttributes({ boxBgOpacity: v })
          }),
          el(RangeControl, {
            label: __('Zaokrąglenie (px)', 'mroomy_s'),
            min: 0, max: 64, step: 1,
            value: boxRadius,
            onChange: (v) => setAttributes({ boxRadius: v })
          })
        ),
        el(
          PanelBody,
          { title: __('Przycisk', 'mroomy_s'), initialOpen: false },
          el(TextControl, {
            label: __('Tekst przycisku', 'mroomy_s'),
            value: buttonText,
            onChange: (v) => setAttributes({ buttonText: v })
          }),
          el(URLInput, {
            value: buttonUrl,
            onChange: (url) => setAttributes({ buttonUrl: url })
          }),
          el(SelectControl, {
            label: __('Target', 'mroomy_s'),
            value: buttonTarget,
            options: [
              { label: '_self', value: '_self' },
              { label: '_blank', value: '_blank' },
            ],
            onChange: (v) => setAttributes({ buttonTarget: v })
          })
        )
      ),
      el(
        'section',
        { className: className, style: { position: 'relative' } },
        el(
          'div',
          { className: 'relative w-full', style: Object.assign({ height: '655.835px' }, bgStyle) },
          imageUrl && el('img', {
            src: imageUrl,
            className: 'absolute inset-0 w-full h-full object-cover',
            loading: 'eager',
            fetchpriority: 'high',
            alt: attributes.imageAlt || ''
          }),
          el(
            'div',
            { className: 'absolute left-[58px] top-[109px] w-[559px] p-[48px] flex flex-col gap-6 items-start justify-center', style: cardStyle },
            el(RichText, {
              tagName: 'h2',
              placeholder: __('Nagłówek…', 'mroomy_s'),
              className: 'headline-2 text-neutral-text',
              value: title,
              onChange: (v) => setAttributes({ title: v }),
              allowedFormats: ['core/bold', 'core/italic', 'core/line-height']
            }),
            el(RichText, {
              tagName: 'div',
              placeholder: __('Treść…', 'mroomy_s'),
              className: 'title-small-1 text-[#3c3c3b] hero-content',
              value: content,
              onChange: (v) => setAttributes({ content: v }),
              allowedFormats: ['core/bold', 'core/italic', 'core/link']
            }),
            el(
              'div',
              null,
              el(
                'a',
                {
                  className: 'btn-cta inline-flex',
                  href: buttonUrl || '#',
                  target: buttonTarget || '_self',
                  rel: (buttonTarget === '_blank') ? 'noopener noreferrer' : undefined
                },
                buttonText || __('Zobacz nasze projekty', 'mroomy_s')
              )
            )
          )
        )
      )
    );
  }

  registerBlockType('mroomy/top-section', { edit: Edit, save: function() { return null; } });
})();


