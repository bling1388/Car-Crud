  <!-- Left Sidebar - style you can find in sidebar.scss  -->
  <!-- ============================================================== -->
  <aside class="left-sidebar mt-4" data-sidebarbg="skin6" style="position: fixed;">

      <style>
          .sidebar-item {
              position: relative;
              /* Ensure the parent is relative */
          }

          .sidebar-link {
              position: relative;
              /* Make sure this parent is relative */
          }

          .arrow {
              position: absolute;
              /* Positioning the arrow relative to its parent */
              right: 15px;
              /* Adjust where you want the arrow to appear, this puts it 15px from the right side */
              top: 50%;
              /* Center it vertically */
              transform: translateY(-50%);
              /* Fine-tune vertical centering */
              width: 0;
              height: 0;
              border-left: 5px solid transparent;
              /* Arrow left border */
              border-right: 5px solid transparent;
              /* Arrow right border */
              border-top: 5px solid #333;
              /* Change #333 to whatever color your arrow needs to be */
              cursor: pointer;
          }

          /* If you want the arrow to change when the dropdown is active, you can use jQuery to toggle a class or use the sibling and parent selectors in CSS. */
          .sidebar-item.open .arrow {
              border-top: none;
              border-bottom: 5px solid #333;
              /* Creates the 'up' arrow when the dropdown is active. Change color as needed */
          }

          .dropdown-content {
              display: none;
              /* Hide by default */
              /* Remove position, left, and top properties */
              background-color: #f9f9f9;
              /* padding-left: 50px; */
              min-width: 160px;
              box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
              z-index: 1;
              /* Ensure it stays on top of other elements */
              width: 100%;
              /* make it as wide as the parent */
              border-top: 1px solid rgba(0, 0, 0, 0.1);
              /* Optional: add a top border for separation */
          }


          .dropdown-content a {
              color: black;
              padding: 12px 16px;
              /* existing padding */
              padding-left: 40px;
              /* new left padding to indent the links */
              text-decoration: none;
              display: block;
          }

          .dropdown-content a:hover {
              background-color: #f1f1f1;
              /* Example hover state color */
          }
      </style>

      <!-- Sidebar scroll-->
      <div class="scroll-sidebar">
          <!-- Sidebar navigation-->

          <nav class="sidebar-nav mt-5">
              <ul id="sidebarnav">
                  <!-- User Profile-->
                  <li class="sidebar-item pt-2">
                      <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.dashboard') }}"
                          aria-expanded="false">
                          <i class="fas fa-box" aria-hidden="true"></i>
                          <span class="hide-menu">Dashboard</span>
                      </a>
                  </li>

                  <li class="sidebar-item">
                      <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.cars') }}"
                          aria-expanded="false">
                          <i class="fas fa-car" aria-hidden="true"></i>
                          <span class="hide-menu">Cars</span>
                      </a>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link waves-effect waves-dark sidebar-link"
                          href="{{ route('admin.categories') }}" aria-expanded="false">
                          <i class="fas fa-th" aria-hidden="true"></i>
                          <span class="hide-menu">Category</span>
                      </a>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link waves-effect waves-dark sidebar-link"
                          href="{{ route('admin.subcategories') }}" aria-expanded="false">
                          <i class="fas fa-tasks" aria-hidden="true"></i>
                          <span class="hide-menu">Subcategory</span>
                      </a>
                  </li>
                  <li class="sidebar-item mt-5">
                      <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('cars') }}"
                          aria-expanded="false">
                          <i class="fas fa-rocket" aria-hidden="true"></i>
                          <span class="hide-menu fw-bold">Go to front end</span>
                      </a>
                  </li>

              </ul>

          </nav>
          <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
      <script>
          $(document).ready(function() {
              $('.sidebar-link2').click(function(event) {
                  event.preventDefault(); // Prevent the default action of the anchor tag
                  $(this).next('.dropdown-content')
                      .slideToggle(); // This will toggle the display of the dropdown-content
              });
          });
      </script>

  </aside>
  <!-- ============================================================== -->
  <!-- End Left Sidebar - style you can find in sidebar.scss  -->
  <!-- ============================================================== -->
