import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StaffAllComponent } from './staff-all.component';

describe('StaffAllComponent', () => {
  let component: StaffAllComponent;
  let fixture: ComponentFixture<StaffAllComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StaffAllComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StaffAllComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
