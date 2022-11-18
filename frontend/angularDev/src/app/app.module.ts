import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ToastrModule } from 'ngx-toastr';
import { FormsModule, ReactiveFormsModule } from  '@angular/forms';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import {OrderService} from "./service/order.service";
import { OrderComponent } from './component/order/order.component';
import { HeaderComponent } from './component/header/header.component';
import { GridModule } from '@progress/kendo-angular-grid';
import { EditOrderComponent } from './component/edit-order/edit-order.component';
import {DialogModule} from "@progress/kendo-angular-dialog";
import {FormFieldModule, TextBoxModule} from "@progress/kendo-angular-inputs";
import {ButtonModule} from "@progress/kendo-angular-buttons";
import {LabelModule} from "@progress/kendo-angular-label";

@NgModule({
  declarations: [
    AppComponent,
    OrderComponent,
    HeaderComponent,
    EditOrderComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    GridModule,
    ToastrModule.forRoot(
      {
        timeOut: 3500,
        positionClass: 'toast-top-center',
        preventDuplicates: true
      }
    ),
    DialogModule,
    FormFieldModule,
    TextBoxModule,
    ButtonModule,
    LabelModule
  ],
  providers: [OrderService],
  bootstrap: [AppComponent]
})
export class AppModule {

}
