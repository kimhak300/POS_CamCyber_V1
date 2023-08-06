// ==========================================================>> Core Library
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

// ==========================================================>> Custom Library
import { SharedModule } from 'app/shared/shared.module';
import { ScrollbarModule } from 'helpers/directives/scrollbar';
import { POSComponent } from './pos/pos.component';
import { ViewComponent } from './view/view.component';
import { ProductItemComponent } from './product-item/product-item.component';

const posRoutes: Routes = [
    {
        path: '',
        component: POSComponent
    }
];

@NgModule({
    imports: [
        SharedModule,
        ScrollbarModule,
        RouterModule.forChild(posRoutes),
    ],
    declarations: [
        POSComponent,
        ViewComponent,
        ProductItemComponent
    ],
})
export class PosModule { }
