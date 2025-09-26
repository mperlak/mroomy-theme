/* global wp */
(function() {
  const { registerBlockType } = wp.blocks;
  const { __ } = wp.i18n;
  const {
    InspectorControls,
    MediaUpload,
    MediaUploadCheck,
    RichText,
  } = wp.blockEditor || wp.editor;
  const {
    PanelBody,
    Button,
    SelectControl,
    TextControl,
    BaseControl,
  } = wp.components;
  const { Fragment } = wp.element;
  const el = wp.element.createElement;

  const VARIANT_OPTIONS = [
    { value: 'normal', label: __( 'Standardowy', 'mroomy_s' ) },
    { value: 'large', label: __( 'Powiększony', 'mroomy_s' ) },
  ];

  function MediaField(props) {
    const {
      label,
      onSelect,
      onRemove,
      imageId,
      imageUrl,
      alt,
      onChangeAlt,
    } = props;

    const preview = imageUrl
      ? el(
          Fragment,
          null,
          el(
            'div',
            { className: 'banner-cta__media-preview' },
            el('img', { src: imageUrl, alt: '' })
          ),
          el(
            Button,
            {
              isDestructive: true,
              variant: 'link',
              onClick: onRemove,
            },
            __( 'Usuń obraz', 'mroomy_s' )
          )
        )
      : null;

    return el(
      PanelBody,
      { title: label, initialOpen: false },
      el(
        MediaUploadCheck,
        null,
        el(MediaUpload, {
          onSelect,
          allowedTypes: ['image'],
          value: imageId,
          render: ({ open }) =>
            el(
              Button,
              { onClick: open, variant: 'secondary' },
              imageUrl
                ? __( 'Zmień obraz', 'mroomy_s' )
                : __( 'Wybierz obraz', 'mroomy_s' )
            ),
        })
      ),
      preview,
      el(TextControl, {
        label: __( 'Tekst alternatywny', 'mroomy_s' ),
        value: alt || '',
        onChange: onChangeAlt,
      })
    );
  }

  function Edit(props) {
    const { attributes, setAttributes, className } = props;
    const {
      variant = 'normal',
      title,
      subtitle,
      buttonText,
      buttonUrl,
      buttonTarget = '_self',
      desktopImageId,
      desktopImageUrl,
      desktopImageAlt,
      mobileImageId,
      mobileImageUrl,
      mobileImageAlt,
    } = attributes;

    const defaultButtonLabel =
      variant === 'large'
        ? __( 'Poznaj mroomy', 'mroomy_s' )
        : __( 'Zobacz cennik', 'mroomy_s' );

    const blockClasses = [
      'banner-cta',
      `banner-cta--${variant}`,
      'banner-cta--editor',
      className,
    ]
      .filter(Boolean)
      .join(' ');

    const mediaSection = (desktopImageUrl || mobileImageUrl)
      ? el(
          'div',
          { className: 'banner-cta__media' },
          mobileImageUrl
            ? el('img', {
                src: mobileImageUrl,
                alt: '',
                className: 'banner-cta__media-img banner-cta__media-img--mobile',
              })
            : null,
          desktopImageUrl
            ? el('img', {
                src: desktopImageUrl,
                alt: '',
                className: 'banner-cta__media-img banner-cta__media-img--desktop',
              })
            : null
        )
      : null;

    return el(
      Fragment,
      null,
      el(
        InspectorControls,
        null,
        el(
          PanelBody,
          { title: __( 'Wariant', 'mroomy_s' ), initialOpen: true },
          el(SelectControl, {
            label: __( 'Układ', 'mroomy_s' ),
            value: variant,
            options: VARIANT_OPTIONS,
            onChange: (value) => setAttributes({ variant: value }),
          })
        ),
        el(MediaField, {
          label: __( 'Obraz desktop', 'mroomy_s' ),
          imageId: desktopImageId,
          imageUrl: desktopImageUrl,
          alt: desktopImageAlt,
          onSelect: (media) =>
            setAttributes({
              desktopImageId: media.id,
              desktopImageUrl: media.url,
              desktopImageAlt: media.alt,
            }),
          onRemove: () =>
            setAttributes({
              desktopImageId: undefined,
              desktopImageUrl: undefined,
              desktopImageAlt: undefined,
            }),
          onChangeAlt: (value) => setAttributes({ desktopImageAlt: value }),
        }),
        el(MediaField, {
          label: __( 'Obraz mobile', 'mroomy_s' ),
          imageId: mobileImageId,
          imageUrl: mobileImageUrl,
          alt: mobileImageAlt,
          onSelect: (media) =>
            setAttributes({
              mobileImageId: media.id,
              mobileImageUrl: media.url,
              mobileImageAlt: media.alt,
            }),
          onRemove: () =>
            setAttributes({
              mobileImageId: undefined,
              mobileImageUrl: undefined,
              mobileImageAlt: undefined,
            }),
          onChangeAlt: (value) => setAttributes({ mobileImageAlt: value }),
        }),
        el(
          PanelBody,
          { title: __( 'Przycisk', 'mroomy_s' ), initialOpen: false },
          el(TextControl, {
            label: __( 'Tekst przycisku', 'mroomy_s' ),
            value: buttonText || '',
            placeholder: defaultButtonLabel,
            onChange: (value) => setAttributes({ buttonText: value }),
          }),
          el(TextControl, {
            label: __( 'Adres URL', 'mroomy_s' ),
            value: buttonUrl || '',
            placeholder: 'https://',
            onChange: (value) => setAttributes({ buttonUrl: value }),
          }),
          el(SelectControl, {
            label: __( 'Target', 'mroomy_s' ),
            value: buttonTarget || '_self',
            options: [
              { label: '_self', value: '_self' },
              { label: '_blank', value: '_blank' },
            ],
            onChange: (value) => setAttributes({ buttonTarget: value }),
          })
        )
      ),
      el(
        'section',
        { className: blockClasses },
        el(
          'div',
          { className: 'banner-cta__inner' },
          mediaSection,
          el(
            'div',
            { className: 'banner-cta__body' },
            el(RichText, {
              tagName: 'h2',
              className: 'banner-cta__title',
              value: title,
              placeholder: __( 'Dodaj nagłówek…', 'mroomy_s' ),
              onChange: (value) => setAttributes({ title: value }),
              allowedFormats: ['core/bold', 'core/italic', 'core/link'],
            }),
            el(RichText, {
              tagName: 'p',
              className: 'banner-cta__subtitle',
              value: subtitle,
              placeholder: __( 'Dodaj tekst pomocniczy…', 'mroomy_s' ),
              onChange: (value) => setAttributes({ subtitle: value }),
              allowedFormats: ['core/bold', 'core/italic', 'core/link'],
            }),
            el(
              'div',
              { className: 'banner-cta__button-wrapper' },
              el(
                Button,
                {
                  variant: 'primary',
                  className: 'btn btn-primary btn-size-lg font-extrabold',
                  disabled: true,
                },
                buttonText || defaultButtonLabel
              )
            )
          )
        )
      )
    );
  }

  registerBlockType('mroomy/banner-cta', {
    edit: Edit,
    save: function() {
      return null;
    },
  });
})();


