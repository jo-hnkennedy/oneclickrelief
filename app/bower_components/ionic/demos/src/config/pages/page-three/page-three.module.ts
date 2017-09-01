import { NgModule } from '@angular/core';
import { IonicPageModule } from '../../../../../src';

import { PageThree } from './page-three';

@NgModule({
  declarations: [
    PageThree,
  ],
  imports: [
    IonicPageModule.forChild(PageThree),
  ],
  entryComponents: [
    PageThree,
  ]
})
export class PageThreeModule {}
