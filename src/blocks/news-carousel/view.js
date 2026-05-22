import { store, getElement } from '@wordpress/interactivity';

const autoplayIntervals = new Map();

function getCardStep(container) {
	return container?.offsetWidth ?? 0;
}

store('runpartner/news-carousel', {
	actions: {
		carouselNext() {
			const wrapper = getElement().ref.closest('.news-carousel');
			if (!wrapper) return;
			const container = wrapper.querySelector('.news-carousel-track');
			if (!container) return;
			const step = getCardStep(container);
			const maxScroll = container.scrollWidth - container.clientWidth;
			if (container.scrollLeft + step >= maxScroll) {
				container.scrollTo({ left: 0, behavior: 'smooth' });
			} else {
				container.scrollBy({ left: step, behavior: 'smooth' });
			}
		},

		carouselPrev() {
			const wrapper = getElement().ref.closest('.news-carousel');
			if (!wrapper) return;
			const container = wrapper.querySelector('.news-carousel-track');
			if (!container) return;
			const step = getCardStep(container);
			if (container.scrollLeft - step <= 0) {
				container.scrollTo({
					left: container.scrollWidth,
					behavior: 'smooth',
				});
			} else {
				container.scrollBy({ left: -step, behavior: 'smooth' });
			}
		},

		pauseCarousel() {
			const wrapper = getElement().ref.closest('.news-carousel');
			if (!wrapper) return;
			const id = wrapper.dataset.carouselId;
			if (id && autoplayIntervals.has(id)) {
				clearInterval(autoplayIntervals.get(id));
				autoplayIntervals.delete(id);
			}
		},

		resumeCarousel() {
			const wrapper = getElement().ref.closest('.news-carousel');
			if (!wrapper) return;
			const id = wrapper.dataset.carouselId;
			if (!id) return;
			if (autoplayIntervals.has(id)) {
				clearInterval(autoplayIntervals.get(id));
			}
			autoplayIntervals.set(
				id,
				setInterval(() => {
					const el = document.querySelector(`[data-carousel-id="${id}"]`);
					if (!el) return;
					const container = el.querySelector('.news-carousel-track');
					if (!container) return;
					const step = getCardStep(container);
					const maxScroll = container.scrollWidth - container.clientWidth;
					if (container.scrollLeft + step >= maxScroll) {
						container.scrollTo({ left: 0, behavior: 'smooth' });
					} else {
						container.scrollBy({ left: step, behavior: 'smooth' });
					}
				}, 5000)
			);
		},
	},

	callbacks: {
		initCarousel() {
			document.querySelectorAll('.news-carousel').forEach((el) => {
				const id = `news-carousel-${Math.random().toString(36).slice(2, 9)}`;
				el.dataset.carouselId = id;
				autoplayIntervals.set(
					id,
					setInterval(() => {
						const container = el.querySelector('.news-carousel-track');
						if (!container) return;
						const step = getCardStep(container);
						const maxScroll = container.scrollWidth - container.clientWidth;
						if (container.scrollLeft + step >= maxScroll) {
							container.scrollTo({ left: 0, behavior: 'smooth' });
						} else {
							container.scrollBy({ left: step, behavior: 'smooth' });
						}
					}, 5000)
				);
			});
		},
	},
});
