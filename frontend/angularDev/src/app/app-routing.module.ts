import {NgModule} from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {OrderComponent} from "./component/order/order.component";


const routes: Routes = [
  {
    path: 'dashboard',
    component: OrderComponent },
  {
    path: '',
    redirectTo: 'dashboard',
    pathMatch: 'full'
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
