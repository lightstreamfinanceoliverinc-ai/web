/**
 * ACH Transfer Form - Frontend JavaScript
 * Handles form validation, submission, and user feedback
 * Author: Lightstream Finance
 */

document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const form = document.querySelector('.needs-validation');
    if (!form) return;

    const spinner = document.getElementById('loading-spinner');
    const submitButton = form.querySelector('button[type="submit"]');
    const alertContainer = document.getElementById('alert-status');
    
    let isSubmitting = false;

    form.querySelectorAll('input, select, textarea').forEach((field) => {
        const eventType = field.tagName === 'SELECT' ? 'change' : 'input';
        
        field.addEventListener(eventType, () => {
            const isEmpty = !field.value.trim();
            const isValid = field.checkValidity();
            const fieldContainer = field.closest('div');
            const validFeedback = fieldContainer?.querySelector('.validation-feedback.valid');
            const invalidFeedback = fieldContainer?.querySelector('.validation-feedback.invalid');

            field.classList.remove('is-valid', 'is-invalid');
            validFeedback?.classList.add('hidden');
            invalidFeedback?.classList.add('hidden');

            if (!isEmpty) {
                if (isValid) {
                    field.classList.add('is-valid');
                    validFeedback?.classList.remove('hidden');
                } else {
                    field.classList.add('is-invalid');
                    invalidFeedback?.classList.remove('hidden');
                }
            }
        });
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        event.stopPropagation();

        if (isSubmitting) return;

        form.classList.remove('was-validated');
        form.querySelectorAll('.is-valid, .is-invalid').forEach((el) => {
            el.classList.remove('is-valid', 'is-invalid');
        });

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            form.querySelector(':invalid')?.focus();
            return;
        }

        const formData = new FormData(form);
        const backendURL = 'AjaxForm.php';

        if (spinner) spinner.classList.remove('hidden');
        if (submitButton) submitButton.disabled = true;
        form.querySelectorAll('input, select, textarea, button').forEach((element) => {
            if (element !== submitButton) element.disabled = true;
        });
        isSubmitting = true;

        try {
            const response = await fetch(backendURL, {
                method: 'POST',
                body: formData,
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) {
                throw new Error(`⚠️ Network error: ${response.status}`);
            }

            let data;
            try {
                data = await response.json();
            } catch {
                throw new Error('⚠️ Invalid JSON response from server.');
            }

            const wasSuccessful = !!data?.success;
            const statusMessage = data?.message || (wasSuccessful ? 'Success.' : 'An error occurred.');
            const invalidFieldName = data?.field;

            if (invalidFieldName) {
                const invalidField = form.querySelector(`[name="${CSS.escape(invalidFieldName)}"]`);
                if (invalidField) {
                    invalidField.classList.add('is-invalid');
                    invalidField.focus();
                    form.classList.remove('was-validated');
                }
            }

            if (alertContainer) {
                alertContainer.className = `alert alert-${wasSuccessful ? 'success' : 'danger'}`;
                alertContainer.textContent = statusMessage;
                alertContainer.classList.remove('hidden');
                alertContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            if (wasSuccessful) {
                form.reset();
                form.classList.remove('was-validated');
                form.querySelectorAll('.is-valid, .is-invalid').forEach((el) => {
                    el.classList.remove('is-valid', 'is-invalid');
                });
            }
        } catch (error) {
            console.error(error);
            
            if (alertContainer) {
                alertContainer.className = 'alert alert-danger';
                alertContainer.textContent = error?.message || 'Unexpected error.';
                alertContainer.classList.remove('hidden');
            }
        } finally {
            if (spinner) spinner.classList.add('hidden');
            if (submitButton) submitButton.disabled = false;
            form.querySelectorAll('input, select, textarea, button').forEach((element) => {
                if (element !== submitButton) element.disabled = false;
            });
            isSubmitting = false;
        }
    });
});