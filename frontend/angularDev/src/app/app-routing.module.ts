import {createComponent, NgModule} from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {CreateComponent} from "./component/create/create.component";
import {EditComponent} from "./component/edit/edit.component";
import {AppComponent} from "./app.component";
import {DashboardComponent} from "./component/dashboard/dashboard.component";


const routes: Routes = [
  {
    path: 'dashboard',
    component: DashboardComponent },
  {
    path :'create',
    component : CreateComponent
  },
  {
    path : 'edit/:id',
    component : EditComponent
  },
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
