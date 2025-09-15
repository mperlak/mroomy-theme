(function (wp) {
	const { __ } = wp.i18n;
	const { registerBlockType } = wp.blocks;
	const { InspectorControls, MediaUpload, MediaUploadCheck, RichText, URLInputButton } = wp.blockEditor;
	const { PanelBody, Button, TextControl, TextareaControl } = wp.components;
	const { Fragment, useState } = wp.element;

	registerBlockType('mroomy/top-stats', {
		edit: function (props) {
			const { attributes, setAttributes } = props;
			const { stats } = attributes;

			const updateStat = (index, field, value) => {
				const newStats = [...stats];
				newStats[index] = {
					...newStats[index],
					[field]: value
				};
				setAttributes({ stats: newStats });
			};

			const addStat = () => {
				if (stats.length < 3) {
					const newStats = [...stats, {
						number: '',
						description: '',
						imageUrl: '',
						imageId: 0,
						buttonText: '',
						buttonUrl: ''
					}];
					setAttributes({ stats: newStats });
				}
			};

			const removeStat = (index) => {
				const newStats = stats.filter((_, i) => i !== index);
				setAttributes({ stats: newStats });
			};

			return (
				<Fragment>
					<InspectorControls>
						<PanelBody title={__('Stats Settings', 'mroomy')}>
							<p>{__('Edit each statistic in the block editor.', 'mroomy')}</p>
							{stats.length < 3 && (
								<Button
									variant="primary"
									onClick={addStat}
								>
									{__('Add Statistic', 'mroomy')}
								</Button>
							)}
						</PanelBody>
					</InspectorControls>

					<div className="mroomy-top-stats-editor">
						<div className="stats-container">
							{stats.map((stat, index) => (
								<div key={index} className="stat-item-editor">
									<div className="stat-controls">
										<Button
											variant="link"
											isDestructive
											onClick={() => removeStat(index)}
											className="remove-stat"
										>
											{__('Remove', 'mroomy')}
										</Button>
									</div>

									<div className="stat-number">
										<RichText
											tagName="div"
											value={stat.number}
											onChange={(value) => updateStat(index, 'number', value)}
											placeholder={__('1500+', 'mroomy')}
											className="stat-number-input"
										/>
									</div>

									<div className="stat-description">
										<TextareaControl
											label={__('Description', 'mroomy')}
											value={stat.description}
											onChange={(value) => updateStat(index, 'description', value)}
											placeholder={__('zadowolonych klientów', 'mroomy')}
											help={__('Use new line for multi-line text', 'mroomy')}
										/>
									</div>

									<div className="stat-image">
										<MediaUploadCheck>
											<MediaUpload
												onSelect={(media) => {
													const newStats = [...stats];
													newStats[index] = {
														...newStats[index],
														imageUrl: media.url,
														imageId: media.id
													};
													setAttributes({ stats: newStats });
												}}
												allowedTypes={['image']}
												value={stat.imageId}
												render={({ open }) => (
													<div>
														{stat.imageUrl ? (
															<div className="image-wrapper">
																<img src={stat.imageUrl} alt="" />
																<Button
																	variant="link"
																	isDestructive
																	onClick={() => {
																		const newStats = [...stats];
																		newStats[index] = {
																			...newStats[index],
																			imageUrl: '',
																			imageId: 0
																		};
																		setAttributes({ stats: newStats });
																	}}
																>
																	{__('Remove Image', 'mroomy')}
																</Button>
															</div>
														) : (
															<Button
																variant="secondary"
																onClick={open}
															>
																{__('Add Image/Icon', 'mroomy')}
															</Button>
														)}
													</div>
												)}
											/>
										</MediaUploadCheck>
									</div>

									<div className="stat-button">
										<TextControl
											label={__('Button Text', 'mroomy')}
											value={stat.buttonText}
											onChange={(value) => updateStat(index, 'buttonText', value)}
											placeholder={__('Zobacz film', 'mroomy')}
										/>
										{stat.buttonText && (
											<TextControl
												label={__('Button URL', 'mroomy')}
												value={stat.buttonUrl}
												onChange={(value) => updateStat(index, 'buttonUrl', value)}
												placeholder={__('https://...', 'mroomy')}
												type="url"
											/>
										)}
									</div>
								</div>
							))}
						</div>

						{stats.length < 3 && (
							<div className="add-stat-container">
								<Button
									variant="primary"
									onClick={addStat}
								>
									{__('Add Statistic', 'mroomy')}
								</Button>
							</div>
						)}
					</div>

					<style jsx>{`
						.mroomy-top-stats-editor {
							padding: 20px;
							background: #f5f5f5;
							border-radius: 8px;
						}
						.stats-container {
							display: flex;
							gap: 40px;
							justify-content: center;
							flex-wrap: wrap;
						}
						.stat-item-editor {
							background: white;
							padding: 20px;
							border-radius: 8px;
							width: 300px;
							position: relative;
						}
						.stat-controls {
							position: absolute;
							top: 10px;
							right: 10px;
						}
						.stat-number-input {
							font-size: 48px;
							font-weight: 800;
							text-align: center;
							color: #222222;
							margin-bottom: 10px;
							border: 1px solid transparent;
							padding: 5px;
						}
						.stat-number-input:focus {
							border: 1px solid #ddd;
							outline: none;
						}
						.stat-description {
							margin-bottom: 15px;
						}
						.stat-description textarea {
							width: 100%;
							min-height: 60px;
							text-align: center;
						}
						.stat-image {
							text-align: center;
							margin-bottom: 15px;
						}
						.stat-image img {
							max-width: 150px;
							height: auto;
						}
						.image-wrapper {
							display: flex;
							flex-direction: column;
							align-items: center;
							gap: 10px;
						}
						.stat-button {
							margin-top: 15px;
						}
						.add-stat-container {
							text-align: center;
							margin-top: 20px;
						}
					`}</style>
				</Fragment>
			);
		},

		save: function () {
			return null;
		}
	});
})(window.wp);