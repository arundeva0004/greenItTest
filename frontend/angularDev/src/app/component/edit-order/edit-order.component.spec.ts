import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditOrderComponent } from './edit-order.component';
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {RouterTestingModule} from "@angular/router/testing";
import {ToastrModule} from "ngx-toastr"
describe('EditOrderComponent', () => {
  let component: EditOrderComponent;
  let fixture: ComponentFixture<EditOrderComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [EditOrderComponent],
      imports: [ HttpClientTestingModule,FormsModule,ReactiveFormsModule,
        RouterTestingModule, ToastrModule.forRoot()],
      providers: [ ]
    })

    await TestBed.configureTestingModule({
      declarations: [ EditOrderComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EditOrderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
