import './bootstrap';
import { setupAutoSubmit, setupConfirmActions, setupLogoutLinks, setupOverviewBars } from './modules/dom-actions';
import { setupEnhancedSelects } from './modules/enhanced-selects';
import { setupExportLoading } from './modules/export-loading';
import { setupGlobalSearch } from './modules/global-search';
import { toggleRoomField } from './modules/schedule-form';
import { setupSidebar } from './modules/sidebar';
import { setupTimetableTooltip } from './modules/timetable-tooltip';

document.addEventListener('DOMContentLoaded', () => {
    toggleRoomField();
    setupSidebar();
    setupGlobalSearch();
    setupEnhancedSelects();
    setupExportLoading();
    setupTimetableTooltip();
    setupConfirmActions();
    setupAutoSubmit();
    setupLogoutLinks();
    setupOverviewBars();
});
