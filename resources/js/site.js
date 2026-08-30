import { initNav } from './site/nav';
import { initI18n } from './site/i18n';
import { initDynamicTranslate } from './site/translate';
import { initAuthPage } from './site/auth';
import { initBrowsePage } from './site/browse';
import { initListingPage } from './site/listing';
import { initPostAdPage } from './site/post-ad';
import { initMessagesPage } from './site/messages';
import { initCalling } from './site/calling';
import { initKycPage } from './site/kyc';
import { initPricingPage } from './site/pricing';
import { initSettingsPage } from './site/settings';
import { initProfilePage } from './site/profile';
import { initFavouritesPage } from './site/favourites';

document.addEventListener('DOMContentLoaded', () => {
    initNav();
    initI18n();
    initDynamicTranslate();
    initCalling(); // listens globally for 'sbrai:start-call', fired from listing/messages pages

    const page = document.body.dataset.page;
    if (page === 'auth') initAuthPage();
    if (page === 'browse') initBrowsePage();
    if (page === 'listing') initListingPage();
    if (page === 'post-ad') initPostAdPage();
    if (page === 'messages') initMessagesPage();
    if (page === 'kyc') initKycPage();
    if (page === 'pricing') initPricingPage();
    if (page === 'settings') initSettingsPage();
    if (page === 'profile') initProfilePage();
    if (page === 'favourites') initFavouritesPage();
});
