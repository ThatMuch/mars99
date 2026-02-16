document.addEventListener('DOMContentLoaded', () => {
  /**
   * Initialize FAQ Accordion
   */
  function initFaqAccordion() {
    const questions = document.querySelectorAll('.faq-question');

    questions.forEach((question) => {
      // Remove existing listeners to avoid duplicates (useful for ACF preview)
      const newQuestion = question.cloneNode(true);
      question.parentNode.replaceChild(newQuestion, question);

      newQuestion.addEventListener('click', function () {
        const expanded = this.getAttribute('aria-expanded') === 'true';
        const controls = this.getAttribute('aria-controls');
        const answer = document.getElementById(controls);

        if (!answer) return;

        // Toggle state
        this.setAttribute('aria-expanded', !expanded);
        answer.setAttribute('aria-hidden', expanded);

        if (!expanded) {
          answer.classList.add('active');
          answer.style.maxHeight = answer.scrollHeight + 'px';
        } else {
          answer.classList.remove('active');
          answer.style.maxHeight = '0px';
        }
      });
    });
  }

  // Initialize on load
  initFaqAccordion();

  // Initialize in ACF Admin
  if (window.acf) {
    window.acf.addAction('render_block_preview/type=faq', initFaqAccordion);
  }
});
