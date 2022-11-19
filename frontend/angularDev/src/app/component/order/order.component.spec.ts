import {ComponentFixture, TestBed, async, fakeAsync} from '@angular/core/testing';
import { OrderComponent } from './order.component';
import {OrderService} from "../../service/order.service";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {NotificationService} from "../../service/notification.service";
import {RouterTestingModule} from "@angular/router/testing";
import {ToastrModule, ToastrService} from "ngx-toastr";
import {of} from "rxjs";
import {HttpClient} from "@angular/common/http";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";


describe('OrderComponent', () => {

  let component: OrderComponent;
  let fixture: ComponentFixture<OrderComponent>;
  let orderService : OrderService;
  let mockApiService : any;
  let orderServiceSpy : jasmine.SpyObj<HttpClient>;
  let ORDERS = [
    {id:"1",name:"Liquid Saffron",state:"NY",zip:"08998",amount:"25.43",qty:"7",item:"XCD45300"},
    {id:"2",name:"Mostly Slugs",state:"PA",zip:"19008",amount:"13.30",qty:"2",item:"AAH6748"},
    {id:"3",name:"Jump Stain",state:"CA",zip:"99388",amount:"56.00",qty:"3",item:"MKII4400"},
    {id:"4",name:"Scheckled Sherlock",state:"WA",zip:"88990",amount:"987.56",qty:"1",item:"TR909"}
  ];

  //arrange
  beforeEach(async () => {

     orderServiceSpy = jasmine.createSpyObj( ['getAllOrders','newOrder','deleteOrder','updateOrder']);
    await TestBed.configureTestingModule({
      declarations: [OrderComponent],
      imports: [ HttpClientTestingModule,FormsModule,ReactiveFormsModule,
        RouterTestingModule, ToastrModule.forRoot()],
      providers: [OrderService,
        NotificationService,
        {provide: ToastrService, useClass: ToastrService},
        {provide: orderService, useValue: orderServiceSpy}
      ]
    })
      .compileComponents();

    //act
    orderService =  TestBed.inject(OrderService);
    fixture = TestBed.createComponent(OrderComponent);
    component = fixture.componentInstance;
    orderServiceSpy = TestBed.inject(HttpClient) as jasmine.SpyObj<HttpClient>;

    mockApiService = jasmine.createSpyObj(['loadCSVData','addNewData','updateOrder','deleteData'])
    //httpClientSpy = TestBed.inject(HttpClient) as jasmine.SpyObj<HttpClient>;
    fixture.detectChanges();

  });

  it('should create Dashboard component', () => {
    //assert
    expect(component).toBeTruthy();
  });

  it('should call ngOnInit', () => {

    //act
    const component = fixture.debugElement.componentInstance;
    spyOn(component,"getAllOrders").and.returnValue([]);
    component.ngOnInit();

    //assert
    expect(component.getAllOrders).toBeTruthy();

  })

  it('should call get all orders from csv file', fakeAsync(  () => {

    //act
    spyOn(orderService,"getAllOrders").and.callFake(() => {
      return of(ORDERS);
    });
    component.getAllOrders();

    //assert
    expect(component.gridData).toEqual(ORDERS);

  }))

  it('Should call post method to add new data to csv file', () =>{

    //act
    let orderData = {id:"5",name:"Hardik Pandya",state:"MI",zip:"78262",amount:"150.75", qty:"7", item:"YCS79282" };
    spyOn(component,"newOrder").and.callFake(() => {
      return of(orderData);
    });
    component.newOrder(orderData);

    //assert
    expect(component.newOrder).toHaveBeenCalled();
  })

});
