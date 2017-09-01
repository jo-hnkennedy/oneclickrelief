import { Component } from '@angular/core';
import { IonicPage } from '../../../../../..';

@IonicPage()
@Component({
  template: `
  <ion-header>
    <ion-navbar>
      <button ion-button menuToggle>
        <ion-icon name="menu"></ion-icon>
      </button>
      <ion-title>Page 2</ion-title>
    </ion-navbar>
  </ion-header>
  <ion-content padding>
    <h1>Page 2</h1>
  </ion-content>
  `
})
export class PageTwo {}
