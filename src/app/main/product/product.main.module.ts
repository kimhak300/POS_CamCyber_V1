// ==========================================================>> Core Library
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { MatTableModule } from '@angular/material/table';
import { ScrollbarModule } from 'helpers/directives/scrollbar';

// ==========================================================>> Custom Library
// import { ProductModule } from 'app/main/product/product/product.module';

const routes: Routes = [
    {
        path: '',
        children: [
            {
                path: 'all',
                loadChildren: () =>
                    import('app/main/product/product/product.module').then(
                        (m) => m.ProductModule
                    ),
            },
            {
                path: 'types',
                loadChildren: () =>
                    import('app/main/product/type/product-type.module').then(
                        (m) => m.ProductTypeModule
                    ),
            },
        ],
    },
];

@NgModule({
    imports: [RouterModule.forChild(routes), MatTableModule, ScrollbarModule],
    exports: [],
})
export class ProductMainModule {}
