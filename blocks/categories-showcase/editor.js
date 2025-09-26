/* global wp */
(function() {
    const { registerBlockType } = wp.blocks;
    const { __ } = wp.i18n;
    const {
        InspectorControls,
        useBlockProps
    } = wp.blockEditor || wp.editor;
    const {
        PanelBody,
        TextControl,
        ToggleControl,
        CheckboxControl,
        BaseControl
    } = wp.components;
    const { Fragment, useState, useEffect } = wp.element;
    const { useSelect } = wp.data;
    const el = wp.element.createElement;
    const { ServerSideRender } = wp.editor || wp.serverSideRender;

    registerBlockType('mroomy/categories-showcase', {
        edit: function(props) {
            const { attributes, setAttributes } = props;
            const {
                title,
                showTitle,
                selectedCategories,
                customLabels,
                enableCarousel,
                showNavigation
            } = attributes;

            const blockProps = useBlockProps();

            // Fetch ALL product categories (including nested)
            const categories = useSelect((select) => {
                const { getEntityRecords } = select('core');
                return getEntityRecords('taxonomy', 'product_cat', {
                    per_page: -1,
                    hide_empty: false,
                    orderby: 'name',
                    order: 'asc'
                });
            }, []);

            // Helper function to build hierarchical category name
            const getCategoryHierarchicalName = (category, allCategories) => {
                let name = category.name;
                let parentId = category.parent;
                let depth = 0;

                while (parentId && parentId !== 0 && depth < 5) { // Max 5 levels to prevent infinite loops
                    const parent = allCategories.find(cat => cat.id === parentId);
                    if (parent) {
                        name = parent.name + ' → ' + name;
                        parentId = parent.parent;
                    } else {
                        break;
                    }
                    depth++;
                }

                return name;
            };

            // Sort categories hierarchically
            const getSortedCategories = (cats) => {
                if (!cats) return [];

                // Create a map for easy lookup
                const catMap = {};
                cats.forEach(cat => {
                    catMap[cat.id] = { ...cat };
                });

                // Build tree structure
                const result = [];
                const addChildren = (parentId = 0, level = 0) => {
                    const children = cats.filter(cat => cat.parent === parentId);
                    children.sort((a, b) => a.name.localeCompare(b.name));

                    children.forEach(child => {
                        result.push({
                            ...child,
                            level: level,
                            hierarchicalName: getCategoryHierarchicalName(child, cats)
                        });
                        addChildren(child.id, level + 1);
                    });
                };

                addChildren(0, 0);
                return result;
            };

            const sortedCategories = getSortedCategories(categories);

            // Handle category selection
            const handleCategoryChange = (termId, isChecked) => {
                let updatedCategories = [...selectedCategories];
                if (isChecked) {
                    updatedCategories.push(termId);
                } else {
                    updatedCategories = updatedCategories.filter(id => id !== termId);
                    // Remove custom label when category is deselected
                    const updatedLabels = { ...customLabels };
                    delete updatedLabels[termId];
                    setAttributes({ customLabels: updatedLabels });
                }
                setAttributes({ selectedCategories: updatedCategories });
            };

            // Handle custom label change
            const handleLabelChange = (termId, label) => {
                const updatedLabels = { ...customLabels };
                if (label) {
                    updatedLabels[termId] = label;
                } else {
                    delete updatedLabels[termId];
                }
                setAttributes({ customLabels: updatedLabels });
            };

            return el(
                Fragment,
                {},
                el(
                    InspectorControls,
                    {},
                    el(
                        PanelBody,
                        {
                            title: __('Ustawienia kategorii', 'mroomy_s'),
                            initialOpen: true
                        },
                        el(
                            TextControl,
                            {
                                label: __('Tytuł sekcji', 'mroomy_s'),
                                value: title,
                                onChange: (value) => setAttributes({ title: value })
                            }
                        ),
                        el(
                            ToggleControl,
                            {
                                label: __('Pokaż tytuł', 'mroomy_s'),
                                checked: showTitle,
                                onChange: (value) => setAttributes({ showTitle: value })
                            }
                        ),
                        el(
                            BaseControl,
                            {
                                label: __('Wybierz kategorie', 'mroomy_s'),
                                help: __('Zaznacz kategorie do wyświetlenia', 'mroomy_s')
                            },
                            sortedCategories.length > 0 ? el(
                                'div',
                                { className: 'mroomy-category-selector' },
                                sortedCategories.map((category) => {
                                    const isSelected = selectedCategories.includes(category.id);
                                    const indent = category.level * 20; // 20px per level
                                    return el(
                                        Fragment,
                                        { key: category.id },
                                        el(
                                            'div',
                                            { style: { marginLeft: indent + 'px' } },
                                            el(
                                                CheckboxControl,
                                                {
                                                    label: category.name + (category.parent ? ' (' + category.hierarchicalName + ')' : ''),
                                                    checked: isSelected,
                                                    onChange: (isChecked) => handleCategoryChange(category.id, isChecked)
                                                }
                                            ),
                                            isSelected && el(
                                                TextControl,
                                                {
                                                    label: __('Własna etykieta dla ', 'mroomy_s') + category.name,
                                                    value: customLabels[category.id] || '',
                                                    onChange: (value) => handleLabelChange(category.id, value),
                                                    placeholder: category.name,
                                                    className: 'ml-4 mb-2',
                                                    style: { marginTop: '8px' }
                                                }
                                            )
                                        )
                                    );
                                })
                            ) : el('p', {}, __('Ładowanie kategorii...', 'mroomy_s'))
                        )
                    ),
                    el(
                        PanelBody,
                        {
                            title: __('Opcje wyświetlania', 'mroomy_s'),
                            initialOpen: false
                        },
                        el(
                            ToggleControl,
                            {
                                label: __('Włącz karuzelę', 'mroomy_s'),
                                checked: enableCarousel,
                                onChange: (value) => setAttributes({ enableCarousel: value }),
                                help: __('Wyświetl kategorie jako karuzelę z przewijaniem', 'mroomy_s')
                            }
                        ),
                        enableCarousel && el(
                            ToggleControl,
                            {
                                label: __('Pokaż strzałki nawigacji', 'mroomy_s'),
                                checked: showNavigation,
                                onChange: (value) => setAttributes({ showNavigation: value }),
                                help: __('Wyświetl strzałki do przewijania karuzeli (tylko desktop)', 'mroomy_s')
                            }
                        )
                    )
                ),
                el(
                    'div',
                    blockProps,
                    el(
                        ServerSideRender,
                        {
                            block: 'mroomy/categories-showcase',
                            attributes: attributes
                        }
                    )
                )
            );
        }
    });
})();