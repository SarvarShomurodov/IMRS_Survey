// resources/js/app.js

import './bootstrap';
import 'select2';

// Global Select2 configuration
window.initializeSkillSelect = function(selector, placeholder, ajaxUrl = '/api/skills/search') {
    $(selector).select2({
        theme: 'bootstrap-5',
        placeholder: placeholder,
        allowClear: true,
        multiple: true,
        minimumInputLength: 2,
        ajax: {
            url: ajaxUrl,
            delay: 300,
            data: function (params) {
                return {
                    q: params.term,
                    limit: 20
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        templateResult: function(skill) {
            if (skill.loading) {
                return skill.text;
            }
            
            var $result = $(
                '<div class="select2-result-skill">' +
                    '<div class="skill-name">' + skill.name + '</div>' +
                    '<div class="skill-meta text-muted small">' +
                        '<span class="badge bg-secondary me-1">' + skill.worker_type + '</span>' +
                        'Kod: ' + skill.group_code +
                    '</div>' +
                '</div>'
            );
            return $result;
        },
        templateSelection: function(skill) {
            return skill.name || skill.text;
        }
    });
};

// Global function to load existing selections
window.loadSkillSelections = function(selector, skillIds) {
    if (skillIds && skillIds.length > 0) {
        $.post('/api/skills/by-ids', {ids: skillIds})
            .done(function(skills) {
                $.each(skills, function(i, skill) {
                    var newOption = new Option(skill.text, skill.id, true, true);
                    $(selector).append(newOption);
                });
                $(selector).trigger('change');
            })
            .fail(function() {
                console.error('Failed to load skill selections');
            });
    }
};

// Global utility functions
window.utils = {
    formatNumber: function(num) {
        return new Intl.NumberFormat('uz-UZ').format(num);
    },
    
    showLoading: function(buttonSelector, loadingText = 'Yuklanmoqda...') {
        const $btn = $(buttonSelector);
        $btn.data('original-text', $btn.html());
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>' + loadingText);
    },
    
    hideLoading: function(buttonSelector) {
        const $btn = $(buttonSelector);
        const originalText = $btn.data('original-text');
        if (originalText) {
            $btn.prop('disabled', false).html(originalText);
        }
    },
    
    showAlert: function(message, type = 'success', container = '.container') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $(container).prepend(alertHtml);
        
        // Auto hide after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    }
};

// Document ready functions
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Global CSRF setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Form validation helper
    window.validateForm = function(formSelector, rules) {
        const form = document.querySelector(formSelector);
        if (!form) return false;
        
        let isValid = true;
        
        Object.keys(rules).forEach(function(fieldName) {
            const field = form.querySelector(`[name="${fieldName}"]`);
            const rule = rules[fieldName];
            
            if (!field) return;
            
            // Remove previous validation classes
            field.classList.remove('is-valid', 'is-invalid');
            
            let fieldValid = true;
            let errorMessage = '';
            
            // Required validation
            if (rule.required && !field.value.trim()) {
                fieldValid = false;
                errorMessage = rule.messages?.required || `${fieldName} majburiy`;
            }
            
            // Min length validation
            if (fieldValid && rule.minLength && field.value.length < rule.minLength) {
                fieldValid = false;
                errorMessage = rule.messages?.minLength || `Kamida ${rule.minLength} ta belgi kiriting`;
            }
            
            // Email validation
            if (fieldValid && rule.email && field.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    fieldValid = false;
                    errorMessage = rule.messages?.email || 'Email manzil noto\'g\'ri';
                }
            }
            
            // Apply validation classes
            if (fieldValid) {
                field.classList.add('is-valid');
            } else {
                field.classList.add('is-invalid');
                isValid = false;
                
                // Show error message
                const feedback = field.parentNode.querySelector('.invalid-feedback') || 
                               field.parentNode.querySelector('.text-danger');
                if (feedback) {
                    feedback.textContent = errorMessage;
                }
            }
        });
        
        return isValid;
    };
});