<style>
  body {
    background-color: #fff;
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
  }

  .sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    width: 250px;
    background-color: #000;
    padding: 20px 0 0 0;
    z-index: 100;
    height: 100vh;
    overflow-y: auto;

    .nav-link {
      color: #fff;
      padding: 12px 20px;
      margin: 0 10px;
      display: block;
      border-radius: 0;
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s;

      &.active {
        background-color: #EFBF04;
        color: #000;
      }

      &:hover {
        background-color: #EFBF04;
        color: #000;
        padding: 12px 20px;
        margin: 0 10px;
      }
    }

    .nav-item {
      margin-bottom: 5px;
    }

    .content {
      margin-left: 250px;
      padding: 30px;
      min-height: 100vh;
    }
  }

  .btn-custom {
    background-color: #EFBF04;
    border-color: #EFBF04;
    font-size: 1rem;
    padding: 8px 16px;

    &:hover {
      background-color: #d8a803;
      border-color: #d8a803;
    }
  }

  h1.main-text {
    color: #EFBF04;
    font-weight: 600;
    font-size: 2.8rem;
  }

  .text-white {
    color: #fff !important;
    font-size: 1.1rem;
  }

  .account-section {
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid #EFBF04;

    img {
      width: 80px;
      height: 80px;
      margin-bottom: 10px;
    }

    p {
      color: #fff;
      margin: 0;
      font-size: 1rem;
      font-weight: 600;
    }
  }

  .card {
    margin-bottom: 20px;
    padding: 20px;
    font-size: 1.1rem;
  }

  .row>* {
    flex-grow: 1;
  }

  #calendar {
    font-size: 1.1rem;
    height: 500px;
    /* Adjusted to match image proportion */
  }

  a {
    color: #d8a803;

    &:hover {
      color: #000;
    }
  }
</style>
